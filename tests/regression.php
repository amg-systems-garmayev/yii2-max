<?php

// Usage: php tests/regression.php /path/to/vendor/autoload.php
$autoload = $argv[1] ?? dirname(__DIR__) . '/vendor/autoload.php';
require $autoload;
if (!class_exists('Yii', false)) {
    require dirname($autoload) . '/yiisoft/yii2/Yii.php';
}
spl_autoload_register(function ($class) {
    $prefix = 'garmayev\\max\\';
    if (strpos($class, $prefix) === 0) {
        require dirname(__DIR__) . '/src/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    }
}, true, true);

use garmayev\max\base\MaxBase;
use garmayev\max\base\Request;
use garmayev\max\EventHandler;
use garmayev\max\types\Attachment;
use garmayev\max\types\Message;
use garmayev\max\types\MessageBody;

set_error_handler(function ($severity, $message, $file, $line) {
    if (error_reporting() & $severity) {
        throw new ErrorException($message, 0, $severity, $file, $line);
    }
    return false;
});
function check($condition, $message) {
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

foreach ([[], ['attachments' => []], ['attachments' => null]] as $config) {
    $body = new MessageBody($config);
    check($body->attachments === [], 'Missing/empty attachments must be readable');
    check($body->markup === [], 'Missing markup must be readable');
    check($body->text === null, 'Missing text must be readable');
}
$body = new MessageBody(['attachments' => [['type' => 'file', 'payload' => ['token' => 'first']]]]);
$body->attachments = [['type' => 'file', 'payload' => ['token' => 'second']]];
check(count($body->attachments) === 1, 'Attachments assignment must replace the previous list');
check($body->attachments[0]->payload['token'] === 'second', 'Attachment must be hydrated');
$body->attachments = [];
check($body->attachments === [], 'Empty list must clear attachments');
$contact = new Attachment(['type' => 'contact', 'payload' => ['name' => 'Test']]);
check($contact->payload->vcf_info === null, 'Contact without vCard must be supported');
check($contact->payload->max_info === [], 'Contact without max_info must be supported');

$request = new Request(['update_type' => 'message_removed', 'chat_id' => 12, 'user_id' => 34, 'message_id' => 'mid']);
check($request->chat_id === 12 && $request->user_id === 34 && $request->message_id === 'mid', 'Event identifiers must load');

foreach (['command', 'contains'] as $method) {
    $bot = (new ReflectionClass(MaxBase::class))->newInstanceWithoutConstructor();
    $bot->request = new Request(['update_type' => 'message_created', 'message' => ['body' => ['text' => '/test hello']]]);
    $handler = (new EventHandler($bot))->init();
    $called = false;
    $handler->$method('test', function (Message $message) use (&$called) {
        $called = $message->body->text === '/test hello';
    });
    check($handler->handle() && $called, $method . ' must dispatch a Message to the user callback');
}
echo "Regression checks passed\n";
