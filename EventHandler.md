## Пример использования:

```php
<?php

use garmayev\max\MaxBot;
use garmayev\max\MessageBuilder;

// Создаем экземпляр бота
$bot = new MaxBot([
    'access_token' => 'YOUR_ACCESS_TOKEN',
    'secret' => 'YOUR_SECRET'
]);

// Получаем обработчик событий
$handler = $bot->handler();

// Обработка команд
$handler->command('start', function ($message) use ($bot) {
    $response = MessageBuilder::create('Привет! Я ваш бот.')
        ->chatId($message->recipient->chat_id)
        ->inlineKeyboard([
            [MessageBuilder::callbackButton('Начать работу', 'start_work')]
        ])
        ->build();
    
    $bot->sendMessage($response, []);
});

$handler->command('help', function ($message) use ($bot) {
    $helpText = "Доступные команды:\n"
        . "/start - Начать работу с ботом\n"
        . "/help - Показать справку\n"
        . "/info - Информация о боте";
    
    $response = MessageBuilder::textOnly($helpText, $message->recipient->chat_id);
    $bot->sendMessage($response, []);
});

// Обработка нажатия кнопок
$handler->callback('start_work', function ($callback) use ($bot) {
    $response = MessageBuilder::create('Отлично! Что вы хотите сделать?')
        ->chatId($callback->user->user_id)
        ->inlineKeyboard([
            [MessageBuilder::callbackButton('Опция 1', 'option_1')],
            [MessageBuilder::callbackButton('Опция 2', 'option_2')]
        ])
        ->build();
    
    $bot->sendMessage($response, []);
});

// Обработка текстовых сообщений
$handler->onMessage(function ($message) use ($bot) {
    $text = strtolower($message->body->text ?? '');
    
    if (strpos($text, 'привет') !== false) {
        $response = MessageBuilder::textOnly(
            "Привет, {$message->sender->first_name}!",
            $message->recipient->chat_id
        );
        $bot->sendMessage($response, []);
        return true;
    }
    
    return false;
});

// Обработка добавления бота в чат
$handler->onBotAdded(function ($request) use ($bot) {
    $chatId = $request->getMessage()->recipient->chat_id;
    
    $response = MessageBuilder::create('Спасибо за добавление в чат! Используйте /help для справки.')
        ->chatId($chatId)
        ->build();
    
    $bot->sendMessage($response, []);
});

// Обработка по регулярному выражению
$handler->regex('/цена (.*)/i', function ($message, $matches) use ($bot) {
    $product = $matches[1];
    $response = MessageBuilder::textOnly(
        "Информация по товару '$product' будет позже.",
        $message->recipient->chat_id
    );
    $bot->sendMessage($response, []);
    return true;
});

// Обработчик по умолчанию
$handler->default(function ($request) use ($bot) {
    if ($request->getUpdate_type() === 'message_created') {
        $response = MessageBuilder::textOnly(
            "Не понимаю ваше сообщение. Используйте /help для справки.",
            $request->getMessage()->recipient->chat_id
        );
        $bot->sendMessage($response, []);
    }
});

// Запускаем обработку событий
$handler->handle();

/**
 * Альтернативный, более простой способ использования:
 */
 
// Создаем бота и сразу настраиваем обработчики
$simpleBot = new MaxBot([
    'access_token' => 'YOUR_ACCESS_TOKEN',
    'secret' => 'YOUR_SECRET'
]);

$simpleBot->handler()
    ->command('ping', function ($message) use ($simpleBot) {
        $simpleBot->sendMessage(['text' => 'pong!', 'chat_id' => $message->recipient->chat_id], []);
    })
    ->command('echo', function ($message) use ($simpleBot) {
        $args = array_slice(explode(' ', $message->body->text), 1);
        $text = implode(' ', $args);
        $simpleBot->sendMessage(['text' => $text, 'chat_id' => $message->recipient->chat_id], []);
    })
    ->onCallback(function ($callback) use ($simpleBot) {
        $simpleBot->sendMessage([
            'text' => "Вы нажали кнопку с payload: {$callback->getPayload()}",
            'chat_id' => $callback->user->user_id
        ], []);
    })
    ->handle();
```    

## Расширенный пример с middleware:
```php
<?php

// Middleware для логирования
$handler->onMessage(function ($message, $next) {
    error_log("Получено сообщение от {$message->sender->user_id}: {$message->body->text}");
    return $next($message);
});

// Middleware для проверки прав доступа
$handler->onMessage(function ($message, $next) use ($adminIds) {
    if (!in_array($message->sender->user_id, $adminIds)) {
        return "У вас нет прав на выполнение этой команды";
    }
    return $next($message);
});

// Middleware для анти-спама
$handler->onMessage(function ($message, $next) {
    static $lastMessageTime = [];
    $userId = $message->sender->user_id;
    $currentTime = time();
    
    if (isset($lastMessageTime[$userId]) && ($currentTime - $lastMessageTime[$userId]) < 1) {
        return "Слишком много сообщений! Подождите немного.";
    }
    
    $lastMessageTime[$userId] = $currentTime;
    return $next($message);
});
```