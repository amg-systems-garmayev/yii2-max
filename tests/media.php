<?php

require __DIR__ . '/regression.php';

use garmayev\max\Max;
use garmayev\max\MessageBuilder;
use garmayev\max\base\MaxBase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;

function mockBot(array $responses, array &$history): Max {
    $stack = HandlerStack::create(new MockHandler($responses));
    // Snapshot multipart bytes before uploadFile() closes its file handle.
    $stack->push(Middleware::mapRequest(function ($request) {
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, (string) $request->getBody());
        rewind($stream);
        return $request->withBody(new \GuzzleHttp\Psr7\Stream($stream));
    }));
    $stack->push(Middleware::history($history));
    $bot = new Max(['access_token' => 'test-token']);
    $property = new ReflectionProperty(MaxBase::class, 'client');
    $property->setAccessible(true);
    $property->setValue($bot, new Client(['handler' => $stack]));
    return $bot;
}

$button = [[MessageBuilder::callbackButton('OK', 'ok')]];
check(MessageBuilder::create()->build() === [], 'Untouched builder must omit attachments');
check(MessageBuilder::create()->removeKeyboard()->build() === ['attachments' => []], 'Keyboard removal must be explicit');
$builder = MessageBuilder::create('Caption')->image('photo')->video('video')->inlineKeyboard($button);
$builder->removeKeyboard()->removeKeyboard();
$attachments = $builder->build()['attachments'];
check(array_column($attachments, 'type') === ['image', 'video'], 'Keyboard removal must preserve media and JSON list indices');
check(array_keys($attachments) === [0, 1], 'Attachments must serialize as a JSON array');
$builder->inlineKeyboard($button)->inlineKeyboard($button);
check(count($builder->build()['attachments']) === 3, 'Setting keyboard again must replace it');
$existing = [['type' => 'image', 'payload' => ['token' => 'existing']], ['type' => 'inline_keyboard', 'payload' => ['buttons' => $button]]];
check(MessageBuilder::create(null, ['attachments' => $existing])->removeKeyboard()->build()['attachments'] === [$existing[0]], 'Existing media must survive editing');
check(MessageBuilder::create(null, ['attachments' => [$existing[1]]])->removeKeyboard()->build()['attachments'] === [], 'Removing existing last keyboard must send empty array');
$history = [];
$bot = mockBot([new Response(200, [], '{"success":true}')], $history);
$bot->editMessage(MessageBuilder::create()->removeKeyboard()->build(), ['message_id' => 'mid']);
check((string) $history[0]['request']->getBody() === '{"attachments":[]}', 'Empty attachments must survive PUT serialization');
check(MessageBuilder::create()->video('token')->build() === ['attachments' => [['type' => 'video', 'payload' => ['token' => 'token']]]], 'Media-only message must omit text');
foreach ([MessageBuilder::create()->file('f')->image('i'), MessageBuilder::create()->file('f')->file('f2')] as $invalid) {
    $failed = false;
    try { $invalid->build(); } catch (InvalidArgumentException $e) { $failed = true; }
    check($failed, 'Unsupported file combination must fail before HTTP');
}
$album = MessageBuilder::create();
for ($i = 0; $i < 12; $i++) { $album->image('token-' . $i); }
check(count($album->build()['attachments']) === 12, '12-image album must be supported');
$album->inlineKeyboard($button);
$failed = false;
try { $album->build(); } catch (InvalidArgumentException $e) { $failed = true; }
check($failed, '13 attachments must be rejected');

$filePath = tempnam(sys_get_temp_dir(), 'max_media_test_');
file_put_contents($filePath, 'test media bytes');
try {
    $cases = [
        ['image', ['url' => 'https://upload.example/image'], '{"photos":{"123":{"token":"image-token"}}}', 'image-token'],
        ['video', ['url' => 'https://upload.example/video', 'token' => 'video-token'], '<retval>1</retval>', 'video-token'],
        ['audio', ['url' => 'https://upload.example/audio', 'token' => 'audio-token'], '<retval>1</retval>', 'audio-token'],
        ['file', ['url' => 'https://upload.example/file'], '{"fileId":123,"token":"file-token"}', 'file-token'],
        ['image', ['url' => 'https://upload.example/image'], '{"token":"flat-image-token"}', 'flat-image-token'],
    ];
    foreach ($cases as [$type, $uploadResponse, $body, $expectedToken]) {
        $history = [];
        $bot = mockBot([
            new Response(200, [], json_encode($uploadResponse)),
            new Response(200, [], $body),
            new Response(200, [], '{"message":{"body":{"mid":"mid","text":"Caption"}}}'),
            new Response(200, [], '{"success":true}'),
        ], $history);
        $uploaded = $bot->upload($filePath, $type, false, 'original.' . $type);
        check($uploaded['token'] === $expectedToken, $type . ' token must be extracted from its documented response');
        $message = MessageBuilder::create('Caption')->$type($uploaded['token'])->inlineKeyboard($button)->build();
        $bot->sendMessage($message, ['chat_id' => 123]);
        $edited = MessageBuilder::create(null, ['attachments' => $message['attachments']])->removeKeyboard()->build();
        check($bot->editMessage($edited, ['message_id' => 'mid'])->isSuccess(), 'PUT response must be readable');
        check($history[0]['request']->getUri()->getQuery() === 'type=' . $type, 'Upload type must be in query');
        $multipart = (string) $history[1]['request']->getBody();
        check(strpos($multipart, 'filename="original.' . $type . '"') !== false, 'Upload must preserve filename');
        check(strpos($multipart, 'Content-Type:') !== false && strpos($multipart, 'test media bytes') !== false, 'Multipart must include content type and bytes');
        foreach ([2 => 'POST', 3 => 'PUT'] as $index => $method) {
            $request = $history[$index]['request'];
            $json = json_decode((string) $request->getBody(), true);
            check($request->getMethod() === $method && $request->getUri()->getPath() === '/messages', 'Correct messages endpoint required');
            check($request->getUri()->getHost() === 'platform-api2.max.ru', 'Current API domain required');
            check($request->getHeaderLine('Authorization') === 'test-token', 'Authorization header required');
            check($json['attachments'][0] === ['type' => $type, 'payload' => ['token' => $expectedToken]], 'Media token must survive HTTP serialization');
            check($request->getUri()->getQuery() === ($method === 'POST' ? 'chat_id=123' : 'message_id=mid'), 'Recipient/message ID must be in query');
        }
        check(count(json_decode((string) $history[3]['request']->getBody(), true)['attachments']) === 1, 'PUT must remove keyboard and retain media');
    }

    // URL download -> multipart upload -> media attachment, without external requests.
    foreach (['image' => 'jpg', 'video' => 'mp4', 'audio' => 'mp3', 'file' => 'pdf'] as $type => $extension) {
        $history = [];
        $bot = mockBot([
            new Response(200, [], json_encode(['url' => 'https://upload.example/media', 'token' => 'media-token'])),
            new Response(200, [], '{"token":"media-token"}'),
        ], $history);
        $previousApp = Yii::$app;
        Yii::$app = (object) ['max' => $bot];
        $downloaded = null;
        $builder = MessageBuilder::create();
        $client = new Client(['handler' => function ($request, $options) use (&$downloaded) {
            $downloaded = $options['sink'];
            check(($options['verify'] ?? true) !== false, 'TLS verification must stay enabled');
            file_put_contents($downloaded, 'downloaded media');
            return \GuzzleHttp\Promise\Create::promiseFor(new Response(200));
        }]);
        $property = new ReflectionProperty(MessageBuilder::class, 'client');
        $property->setAccessible(true);
        $property->setValue($builder, $client);
        try {
            $message = $builder->$type('https://download.example/original.' . $extension . '?signature=test')->build();
            check($message['attachments'][0]['payload']['token'] === 'media-token', 'URL media must use uploaded token');
            check(!file_exists($downloaded), 'Temporary download must be removed');
            check(strpos((string) $history[1]['request']->getBody(), 'filename="original.' . $extension . '"') !== false, 'URL extension must be preserved');
        } finally {
            Yii::$app = $previousApp;
        }
    }

    foreach (['{"photos":{}}', '<retval>0</retval>', 'invalid response'] as $body) {
        $history = [];
        $bot = mockBot([new Response(200, [], '{"url":"https://upload.example/image"}'), new Response(200, [], $body)], $history);
        $failed = false;
        try {
            $bot->upload($filePath, 'image');
        } catch (RuntimeException $e) {
            $failed = true;
        }
        check($failed, 'Failed/invalid upload must never produce a usable token');
    }

    $builder = MessageBuilder::create();
    $downloaded = null;
    $property = new ReflectionProperty(MessageBuilder::class, 'client');
    $property->setAccessible(true);
    $property->setValue($builder, new Client(['handler' => function ($request, $options) use (&$downloaded) {
        $downloaded = $options['sink'];
        throw new RuntimeException('Simulated download failure');
    }]));
    $method = new ReflectionMethod(MessageBuilder::class, 'downloadFromUrl');
    $method->setAccessible(true);
    $failed = false;
    try { $method->invoke($builder, 'https://download.example/image.jpg'); } catch (RuntimeException $e) { $failed = true; }
    check($failed && $downloaded !== null && !file_exists($downloaded), 'Failed download must clean temporary file');
} finally {
    unlink($filePath);
}
echo "Media and keyboard checks passed\n";
