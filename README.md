# Установка

Установка расширения осуществляется через Composer.

### Через командную строку:
```
php composer.phar require --prefer-dist garmayev/yii2-max "*"
```

Или добавьте в `composer.json`:

```
"garmayev/yii2-max": "*"
```

## Использование
### Подключение расширения
После установки расширение автоматически подключается через механизм Bootstrap.
### Инициализация
Для работы с MAX API необходимо инициализировать класс Max с параметрами доступа:

# Документация модуля MAX API SDK для PHP

## Оглавление

- [Введение](#введение)
- [Установка и настройка](#установка-и-настройка)
- [Базовые классы](#базовые-классы)
- [Конструктор сообщений](#конструктор-сообщений)
- [Обработчик событий](#обработчик-событий)
- [Типы данных](#типы-данных)
- [Кнопки и вложения](#Кнопки-и-вложения)
- [Примеры использования](#Примеры-использования)
- [Продвинутые возможности](#Продвинутые-возможности)
- [Обработка ошибок](#Обработка-ошибок)
- [Миграция с других платформ](#Миграция-с-других-платформ)

## Введение
MAX API SDK - это PHP библиотека для работы с API мессенджера MAX. Библиотека предоставляет удобные инструменты для:

- Отправки и получения сообщений
- Обработки различных типов событий
- Создания интерактивных интерфейсов с кнопками
- Работы с файлами, изображениями, локациями и контактами

### Основные возможности

- Полная поддержка API MAX: все методы и типы данных
- Объектно-ориентированный интерфейс: типизация и автодополнение в IDE
- Конструктор сообщений: удобное создание сообщений с различными вложениями
- Обработчик событий: гибкая система обработки входящих событий
- Интеграция с Yii2: поддержка фреймворка Yii2 из коробки
- Middleware поддержка: промежуточная обработка запросов

## Установка и настройка

Требования
- PHP 7.4 или выше
- Composer
- Расширение PHP: json, curl (или GuzzleHttp)

### Установка через Composer
```bash
composer require your-vendor/max-api-sdk
```

### Базовая настройка
```php
use garmayev\max\MaxBot;

// Создание экземпляра бота
$bot = new MaxBot([
'access_token' => 'YOUR_BOT_TOKEN', // Токен бота
'secret' => 'YOUR_WEBHOOK_SECRET',  // Секретный ключ для вебхука
]);

// Настройка вебхука
$response = $bot->setWebhook('https://your-domain.com/webhook', [
    'message_created',
    'message_callback',
    'bot_added'
]);
```

### Интеграция с Yii2
```php
// В конфигурации Yii2
'components' => [
    'maxBot' => [
        'class' => 'garmayev\max\MaxBot',
        'access_token' => 'YOUR_BOT_TOKEN',
        'secret' => 'YOUR_SECRET',
    ],
],
```

```php
// Использование в контроллере
public function actionWebhook()
{
$bot = Yii::$app->maxBot;
$handler = $bot->handler();

    // Настройка обработчиков
    $handler->command('start', function($message) use ($bot) {
        // Обработка команды /start
    });
    
    // Обработка запроса
    return $handler->handle();
}
```

## Базовые классы
### MaxBase
Базовый класс для работы с API MAX.

```php
use garmayev\max\base\MaxBase;

class MyBot extends MaxBase
{
    public function init()
    {
        parent::init();
        // Дополнительная инициализация
    }

    public function customMethod()
    {
        // Пользовательский метод
        return $this->send('POST', 'custom/endpoint', $data);
    }
}
```

#### Методы MaxBase
|Метод	|Описание	|Параметры|
|--|--|--|
|send()	|Отправка запроса к API	|$method, $action, $data, $args|
|init()	|Инициализация компонента	|-|

### Max
Основной класс для работы с ботом.

```php
use garmayev\max\Max;

$bot = new Max([
    'access_token' => 'token',
    'secret' => 'secret'
]);

// Основные методы
$bot->sendMessage($params, $args);     // Отправка сообщения
$bot->editMessage($params, $args);     // Редактирование сообщения
$bot->answers($params, $args);         // Ответы на callback
$bot->setWebhook($url, $types);        // Установка вебхука
```

### MaxBot
Упрощенный класс с интегрированным обработчиком событий.

```php
use garmayev\max\MaxBot;

$bot = new MaxBot($config);
$handler = $bot->handler(); // Получение обработчика событий
```

### Конструктор сообщений
Базовое использование
```php
use garmayev\max\MessageBuilder;

// Простое текстовое сообщение
$message = MessageBuilder::textOnly('Привет мир!', 123456);

// Построение с помощью fluent interface
$message = MessageBuilder::create('Привет!')
->chatId(123456)
->text('Это тестовое сообщение')
->image('image_token_123', 'photo.jpg', 800, 600)
->build();
```

#### Методы конструктора
Статические методы создания

| Метод	                                          | Описание                               |
|----------------------------------------------------|----------------------------------------|
| create($text, $args)	                              | Создает новый конструктор              |
| textOnly($text, $chatId)	                          | Быстрое создание текстового сообщения  |
| withKeyboard($text, $chatId, $keyboard, $oneTime)  | Сообщение с клавиатурой                |
| withImage($text, $chatId, $imageToken)	          | Сообщение с изображением               |

Методы построения

| Метод	                                           | Описание	                       | Возвращает |
|--------------------------------------------------|---------------------------------|------------|
| text($text)	                                     | Установка текста	               | $this      |
| chatId($id)	                                     | Установка ID чата	              | $this      |
| chatType($type)	                                 | Установка типа чата	            | $this      |
| userId($id)	                                     | Установка ID пользователя	      | $this      |
| inlineKeyboard($buttons, $oneTime)	              | Добавление inline-клавиатуры	   | $this      |
| image($token, $filename, $width, $height)	       | Добавление изображения	         | $this      |
| sticker($code)	                                  | Добавление стикера	             | $this      |
| file($token, $filename, $size)	                  | Добавление файла	               | $this      |
| location($lat, $lon)	                            | Добавление локации	             | $this      |
| contact($name, $contactId, $vcfInfo, $vcfPhone)	 | Добавление контакта	            | $this      |
| build()	                                         | Формирование итогового массива	 | array      |

Создание кнопок
Статические методы для кнопок
```php
// Callback кнопка
$button = MessageBuilder::callbackButton('Нажми меня', 'action_payload', 'primary');

// Кнопка-ссылка
$button = MessageBuilder::linkButton('Открыть сайт', 'https://example.com');

// Кнопка-сообщение
$button = MessageBuilder::messageButton('Отправить текст');

// Кнопка запроса контакта
$button = MessageBuilder::requestContactButton('Поделиться контактом');

// Кнопка запроса геолокации
$button = MessageBuilder::requestLocationButton('Отправить локацию', true); // quick = true

// Кнопка открытия мини-приложения
$button = MessageBuilder::openAppButton('Открыть приложение', 'bot_username', 123456, 'data');
```

Создание клавиатуры
```php
// Одиночная кнопка
$keyboard = [[
    MessageBuilder::callbackButton('Кнопка 1', 'btn1')
]];

// Несколько строк
$keyboard = [
    MessageBuilder::row([
        MessageBuilder::callbackButton('Кнопка 1', 'btn1'),
        MessageBuilder::callbackButton('Кнопка 2', 'btn2')
    ]),
    MessageBuilder::row([
        MessageBuilder::linkButton('Сайт', 'https://example.com')
    ])
];

// Использование метода keyboard()
$keyboard = MessageBuilder::keyboard([
    [MessageBuilder::callbackButton('Да', 'yes')],
    [MessageBuilder::callbackButton('Нет', 'no')]
]);
```

Примеры использования
```php
// 1. Сообщение с inline-клавиатурой
$message = MessageBuilder::create('Выберите действие:')
    ->chatId(123456)
    ->inlineKeyboard([
        [
            MessageBuilder::callbackButton('Опция 1', 'option_1', 'primary'),
            MessageBuilder::callbackButton('Опция 2', 'option_2')
        ],
        [
            MessageBuilder::linkButton('Документация', 'https://docs.example.com'),
            MessageBuilder::messageButton('Помощь')
        ]
    ], true) // one_time = true
    ->build();

// 2. Сообщение с медиа-вложениями
$message = MessageBuilder::create('Вот ваши файлы:')
    ->chatId(123456)
    ->image('img_token_1', 'sunset.jpg', 1920, 1080)
    ->file('file_token_1', 'document.pdf', 2048000)
    ->sticker('happy_sticker_001')
    ->location(55.7558, 37.6173) // Москва
    ->build();

// 3. Сообщение с контактом
$message = MessageBuilder::create('Контактная информация:')
    ->chatId(123456)
    ->contact(
        'Иван Иванов',
        789012,
        'BEGIN:VCARD...',
        '+79991234567'
    )
    ->build();
```

## Обработчик событий
Инициализация обработчика
```php
use garmayev\max\MaxBot;

$bot = new MaxBot($config);
$handler = $bot->handler(); // Автоматически парсит входящий запрос
```

Регистрация обработчиков событий
Обработка сообщений
```php
// Обработка всех сообщений
$handler->onMessage(function($message) {
    // $message - объект типа Message
    echo "Получено сообщение: " . $message->body->text;
});

// Обработка определенного типа события
$handler->on('message_created', function($request) {
// $request - объект типа Request
});

// Обработка редактирования сообщений
$handler->onMessageEdited(function($message) {
// Обработка редактированного сообщения
});
```

Обработка команд
```php
// Простая команда
$handler->command('start', function($message) use ($bot) {
$bot->sendMessage([
    'text' => 'Добро пожаловать!',
    'chat_id' => $message->recipient->chat_id
], []);
});

// Команда с аргументами
$handler->command('echo', function($message) use ($bot) {
$args = array_slice(explode(' ', $message->body->text), 1);
$text = implode(' ', $args);

    $bot->sendMessage([
        'text' => $text,
        'chat_id' => $message->recipient->chat_id
    ], []);
});

// Обработка нескольких команд
$handler->command(['start', 'начать'], function($message) {
// Обработка команд /start и /начать
});
```

Обработка callback-кнопок
```php
// Обработка по payload
$handler->callback('action_confirm', function($callback) use ($bot) {
    $bot->sendMessage([
        'text' => 'Вы подтвердили действие!',
        'chat_id' => $callback->user->user_id
    ], []);
});

// Обработка всех callback-ов
$handler->onCallback(function($callback) {
    echo "Нажата кнопка с payload: " . $callback->getPayload();
});
```

Обработка текста
```php
// Проверка на содержание текста
$handler->contains('привет', function($message) {
    // Сработает при "привет", "Привет!", "приветствую" и т.д.
});

// Регулярные выражения
$handler->regex('/цена (.*)/i', function($message, $matches) {
    $product = $matches[1]; // Название товара
    // Обработка запроса цены
});

// Обработка точного совпадения
$handler->onMessage(function($message) {
    if ($message->body->text === 'ping') {
        return 'pong';
    }
    return false;
});
```

Обработка системных событий
```php
// Добавление бота в чат
$handler->onBotAdded(function($request) {
    // Приветственное сообщение
});

// Удаление бота из чата
$handler->onBotRemoved(function($request) {
    // Очистка данных
});

// Старт бота (начало диалога)
$handler->onBotStarted(function($request) {
    // Инициализация диалога
});

// Добавление пользователя в чат
$handler->onUserAdded(function($request) {
    // Приветствие нового пользователя
});

// Удаление пользователя из чата
$handler->onUserRemoved(function($request) {
    // Обновление списка участников
});
```

Middleware поддержка
```php
// Middleware для логирования
$handler->onMessage(function($message, $next) {
    error_log("Сообщение от {$message->sender->user_id}: {$message->body->text}");
    return $next($message);
});

// Middleware для проверки прав
$handler->onMessage(function($message, $next) use ($adminIds) {
    if (!in_array($message->sender->user_id, $adminIds)) {
        return "Доступ запрещен";
    }
    return $next($message);
});

// Middleware для анти-спама
$handler->onMessage(function($message, $next) {
    static $rateLimits = [];
    $userId = $message->sender->user_id;

    if (!isset($rateLimits[$userId])) {
        $rateLimits[$userId] = [];
    }
    
    // Ограничение: 5 сообщений в минуту
    $rateLimits[$userId] = array_filter($rateLimits[$userId], function($time) {
        return time() - $time < 60;
    });
    
    if (count($rateLimits[$userId]) >= 5) {
        return "Слишком много запросов. Подождите немного.";
    }
    
    $rateLimits[$userId][] = time();
    return $next($message);
});
```

Обработчики по умолчанию
```php
// Обработчик по умолчанию для непонятых сообщений
$handler->default(function($request) use ($bot) {
    if ($request->getUpdate_type() === 'message_created') {
        $bot->sendMessage([
            'text' => 'Извините, я не понял ваше сообщение.',
            'chat_id' => $request->getMessage()->recipient->chat_id
        ], []);
    }
});

// Обработчик по умолчанию для всех событий
$handler->on('*', function($request) {
    // Обработка всех событий
});
```

Получение данных из запроса
```php
// В обработчике событий
$handler->onMessage(function($message) use ($handler) {
    // Получение различных объектов
    $request = $handler->getRequest();     // Весь запрос
    $user = $handler->getUser();          // Пользователь
    $chat = $handler->getChat();          // Чат
    $callback = $handler->getCallback();  // Callback (если есть)

    // Использование данных
    $response = "Привет, {$user->first_name}!";
});
```

## Типы данных
### Основные типы

Message

Представляет сообщение пользователя или бота.

```php
$message->sender;        // User - отправитель
$message->recipient;     // Recipient - получатель
$message->timestamp;     // int - время отправки
$message->body;          // MessageBody - содержимое сообщения
$message->link;          // Link - ссылка на другое сообщение
$message->stat;          // Stat - статистика просмотров
```

MessageBody

Содержимое сообщения.

```php
$body->mid;          // string - ID сообщения
$body->seq;          // int - порядковый номер
$body->text;         // string - текст сообщения
$body->attachments;  // array - вложения
$body->markup;       // array - разметка текста
```

User

Информация о пользователе.

```php
$user->user_id;            // int - ID пользователя
$user->first_name;         // string - имя
$user->last_name;          // string - фамилия
$user->name;               // string - полное имя
$user->username;           // string - username
$user->is_bot;             // bool - является ли ботом
$user->avatar_url;         // string - URL аватара
$user->full_avatar_url;    // string - URL полного аватара
```

Chat

Информация о чате.

```php
$chat->chat_id;             // int - ID чата
$chat->type;                // string - тип чата
$chat->status;              // string - статус
$chat->title;               // string - название
$chat->icon;                // Icon - иконка
$chat->participants_count;  // int - количество участников
$chat->is_public;           // bool - публичный ли чат
$chat->link;                // string - ссылка на чат
```

Callback

Данные callback-кнопки.

```php
$callback->timestamp;   // int - время нажатия
$callback->callback_id; // string - ID callback
$callback->payload;     // string - данные кнопки
$callback->user;        // User - пользователь
```

Константы типов событий
```php
use garmayev\max\types\Update;

Update::TYPE_MESSAGE_CREATED;      // Создание сообщения
Update::TYPE_MESSAGE_CALLBACK;     // Нажатие callback-кнопки
Update::TYPE_MESSAGE_EDITED;       // Редактирование сообщения
Update::TYPE_MESSAGE_REMOVED;      // Удаление сообщения
Update::TYPE_BOT_ADDED;            // Добавление бота в чат
Update::TYPE_BOT_REMOVED;          // Удаление бота из чата
Update::TYPE_BOT_STARTED;          // Старт бота
Update::TYPE_BOT_STOPPED;          // Остановка бота
Update::TYPE_USER_ADDED;           // Добавление пользователя
Update::TYPE_USER_REMOVED;         // Удаление пользователя
Update::TYPE_CHAT_TITLE_CHANGED;   // Изменение названия чата
Update::TYPE_MESSAGE_CHAT_CREATED; // Создание чата
```

Константы типов чатов
```php
use garmayev\max\types\Chat;

Chat::TYPE_CHAT;        // Обычный чат

Chat::STATUS_ACTIVE;    // Активный
Chat::STATUS_REMOVED;   // Удален
Chat::STATUS_LEFT;      // Покинут
Chat::STATUS_CLOSED;    // Закрыт
```

## Кнопки и вложения

### Типы кнопок
```php
use garmayev\max\types\buttons\Button;

Button::TYPE_CALLBACK;             // Callback кнопка
Button::TYPE_LINK;                 // Кнопка-ссылка
Button::TYPE_REQUEST_GEO_LOCATION; // Запрос геолокации
Button::TYPE_REQUEST_CONTACT;      // Запрос контакта
Button::TYPE_OPEN_APP;             // Открытие мини-приложения
Button::TYPE_MESSAGE;              // Кнопка-сообщение
```

### Типы вложений
```php
use garmayev\max\types\Attachment;

Attachment::TYPE_LOCATION;        // Локация
Attachment::TYPE_CONTACT;         // Контакт
Attachment::TYPE_IMAGE;           // Изображение
Attachment::TYPE_STICKER;         // Стикер
Attachment::TYPE_FILE;            // Файл
Attachment::TYPE_INLINE_KEYBOARD; // Inline-клавиатура
```

### Работа с вложениями
```php
// Получение вложений из сообщения
$attachments = $message->body->attachments;

foreach ($attachments as $attachment) {
    switch ($attachment->type) {
        case Attachment::TYPE_IMAGE:
            $imageUrl = $attachment->payload->url;
        break;

        case Attachment::TYPE_LOCATION:
            $lat = $attachment->latitude;
            $lon = $attachment->longitude;
            break;
            
        case Attachment::TYPE_CONTACT:
            $contactName = $attachment->payload->name;
            break;
    }
}
```

## Примеры использования
Полный пример бота
```php
<?php

require_once 'vendor/autoload.php';

use garmayev\max\MaxBot;
use garmayev\max\MessageBuilder;

// Конфигурация
$config = [
    'access_token' => getenv('MAX_BOT_TOKEN'),
    'secret' => getenv('MAX_BOT_SECRET'),
];

// Инициализация бота
$bot = new MaxBot($config);

// Обработчики событий
$handler = $bot->handler();

// Команда /start
$handler->command('start', function($message) use ($bot) {
    $response = MessageBuilder::create('🎉 Добро пожаловать!')
        ->chatId($message->recipient->chat_id)
        ->text('Я ваш персональный помощник.')
        ->inlineKeyboard([
            [
                MessageBuilder::callbackButton('📋 Меню', 'show_menu', 'primary'),
                MessageBuilder::callbackButton('ℹ️ Помощь', 'show_help')
            ],
            [
                MessageBuilder::linkButton('🌐 Сайт', 'https://example.com')
            ]
        ])
        ->build();
    
    $bot->sendMessage($response, []);
});

// Команда /help
$handler->command('help', function($message) use ($bot) {
    $helpText = "📚 *Доступные команды:*\n\n"
        . "/start - Начало работы\n"
        . "/help - Эта справка\n"
        . "/menu - Главное меню\n"
        . "/settings - Настройки\n\n"
        . "Также вы можете использовать кнопки ниже:";
    
    $response = MessageBuilder::create($helpText)
        ->chatId($message->recipient->chat_id)
        ->inlineKeyboard([
            [MessageBuilder::callbackButton('🔙 Назад', 'go_back')]
        ])
        ->build();
    
    $bot->sendMessage($response, []);
});

// Обработка callback-кнопок
$handler->callback('show_menu', function($callback) use ($bot) {
    $response = MessageBuilder::create('🏠 *Главное меню*')
        ->chatId($callback->user->user_id)
        ->inlineKeyboard([
            [
                MessageBuilder::callbackButton('📊 Статистика', 'show_stats'),
                MessageBuilder::callbackButton('⚙️ Настройки', 'show_settings')
            ],
            [
                MessageBuilder::callbackButton('📞 Контакты', 'show_contacts'),
                MessageBuilder::callbackButton('📍 Локация', 'request_location')
            ],
            [
                MessageBuilder::callbackButton('🔄 Обновить', 'refresh'),
                MessageBuilder::callbackButton('❓ Помощь', 'show_help', 'secondary')
            ]
        ])
        ->build();
    
    $bot->sendMessage($response, []);
});

// Обработка запроса локации
$handler->callback('request_location', function($callback) use ($bot) {
    $response = MessageBuilder::create('Пожалуйста, поделитесь вашей локацией:')
        ->chatId($callback->user->user_id)
        ->inlineKeyboard([
            [MessageBuilder::requestLocationButton('📍 Отправить локацию', true)]
        ])
        ->build();
    
    $bot->sendMessage($response, []);
});

// Обработка локации
$handler->onMessage(function($message) use ($bot) {
    $attachments = $message->body->attachments ?? [];
    
    foreach ($attachments as $attachment) {
        if ($attachment->type === 'location') {
            $lat = $attachment->latitude;
            $lon = $attachment->longitude;
            
            $response = MessageBuilder::create("📍 Получена локация:\nШирота: $lat\nДолгота: $lon")
                ->chatId($message->recipient->chat_id)
                ->location($lat, $lon) // Отправляем обратно для подтверждения
                ->inlineKeyboard([
                    [MessageBuilder::callbackButton('✅ Спасибо', 'location_received')]
                ])
                ->build();
            
            $bot->sendMessage($response, []);
            return true;
        }
    }
    
    return false;
});

// Обработка по умолчанию
$handler->default(function($request) use ($bot) {
    if ($request->getUpdate_type() === 'message_created') {
        $message = $request->getMessage();
        
        // Эхо-ответ
        $response = MessageBuilder::create("Вы сказали: " . $message->body->text)
            ->chatId($message->recipient->chat_id)
            ->inlineKeyboard([
                [MessageBuilder::callbackButton('Повторить', 'echo_' . time())]
            ])
            ->build();
        
        $bot->sendMessage($response, []);
    }
});

// Middleware для логирования
$handler->onMessage(function($message, $next) {
    $logData = [
        'user_id' => $message->sender->user_id,
        'text' => $message->body->text,
        'timestamp' => date('Y-m-d H:i:s'),
        'chat_id' => $message->recipient->chat_id
    ];
    
    file_put_contents('bot_log.txt', json_encode($logData) . PHP_EOL, FILE_APPEND);
    return $next($message);
});

// Запуск обработки
$handler->handle();
```

Пример админ-панели
```php
<?php

// Бот для админ-панели
class AdminBot
{
    private $bot;
    private $handler;
    private $adminIds = [123456, 789012];
    
    public function __construct()
    {
        $this->bot = new MaxBot([
            'access_token' => getenv('ADMIN_BOT_TOKEN'),
            'secret' => getenv('ADMIN_BOT_SECRET')
        ]);
        
        $this->handler = $this->bot->handler();
        $this->setupHandlers();
    }
    
    private function setupHandlers()
    {
        // Проверка прав доступа
        $this->handler->onMessage(function($message, $next) {
            if (!in_array($message->sender->user_id, $this->adminIds)) {
                return $this->sendMessage($message->recipient->chat_id, '⛔ Доступ запрещен');
            }
            return $next($message);
        });
        
        // Команда /stats
        $this->handler->command('stats', function($message) {
            $stats = $this->getStatistics();
            $this->sendStatsMessage($message->recipient->chat_id, $stats);
        });
        
        // Команда /broadcast
        $this->handler->command('broadcast', function($message) {
            $text = implode(' ', array_slice(explode(' ', $message->body->text), 1));
            $this->broadcastMessage($text);
        });
        
        // Команда /users
        $this->handler->command('users', function($message) {
            $users = $this->getActiveUsers();
            $this->sendUsersList($message->recipient->chat_id, $users);
        });
    }
    
    private function sendStatsMessage($chatId, $stats)
    {
        $text = "📊 *Статистика бота:*\n\n"
            . "👥 Всего пользователей: {$stats['total_users']}\n"
            . "📅 Новых сегодня: {$stats['new_today']}\n"
            . "💬 Сообщений сегодня: {$stats['messages_today']}\n"
            . "📈 Активных пользователей: {$stats['active_users']}\n";
        
        $response = MessageBuilder::create($text)
            ->chatId($chatId)
            ->inlineKeyboard([
                [MessageBuilder::callbackButton('🔄 Обновить', 'refresh_stats')],
                [MessageBuilder::callbackButton('📥 Экспорт', 'export_stats')]
            ])
            ->build();
        
        $this->bot->sendMessage($response, []);
    }
    
    public function handle()
    {
        return $this->handler->handle();
    }
}

// Использование
$adminBot = new AdminBot();
$adminBot->handle();
```

Пример бота с базой данных

```php
<?php

// Бот с интеграцией базы данных
class DatabaseBot
{
    private $bot;
    private $handler;
    private $db;
    
    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->bot = new MaxBot([
            'access_token' => getenv('BOT_TOKEN'),
            'secret' => getenv('BOT_SECRET')
        ]);
        
        $this->handler = $this->bot->handler();
        $this->setupDatabaseHandlers();
    }
    
    private function setupDatabaseHandlers()
    {
        // Регистрация пользователя
        $this->handler->command('register', function($message) {
            $this->registerUser($message);
        });
        
        // Профиль пользователя
        $this->handler->command('profile', function($message) {
            $this->showProfile($message);
        });
        
        // Список пользователей
        $this->handler->command('users', function($message) {
            $this->showUsersList($message);
        });
        
        // Сохранение сообщений в БД
        $this->handler->onMessage(function($message, $next) {
            $this->saveMessage($message);
            return $next($message);
        });
    }
    
    private function registerUser($message)
    {
        $userId = $message->sender->user_id;
        $username = $message->sender->username;
        $firstName = $message->sender->first_name;
        
        $stmt = $this->db->prepare(
            "INSERT INTO users (user_id, username, first_name, registered_at) 
             VALUES (?, ?, ?, NOW())
             ON DUPLICATE KEY UPDATE last_seen = NOW()"
        );
        
        $stmt->execute([$userId, $username, $firstName]);
        
        $response = MessageBuilder::create('✅ Регистрация завершена!')
            ->chatId($message->recipient->chat_id)
            ->inlineKeyboard([
                [MessageBuilder::callbackButton('👤 Профиль', 'show_profile')]
            ])
            ->build();
        
        $this->bot->sendMessage($response, []);
    }
    
    private function saveMessage($message)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO messages 
             (user_id, chat_id, text, attachments, timestamp) 
             VALUES (?, ?, ?, ?, ?)"
        );
        
        $attachments = json_encode($message->body->attachments ?? []);
        $stmt->execute([
            $message->sender->user_id,
            $message->recipient->chat_id,
            $message->body->text,
            $attachments,
            $message->timestamp
        ]);
    }
}
```

## Продвинутые возможности
### Кастомные middleware
```php
// Создание кастомного middleware
class AuthMiddleware
{
    private $allowedUsers;
    
    public function __construct(array $allowedUsers)
    {
        $this->allowedUsers = $allowedUsers;
    }
    
    public function __invoke($message, $next)
    {
        if (!in_array($message->sender->user_id, $this->allowedUsers)) {
            throw new \Exception('Access denied');
        }
        
        return $next($message);
    }
}

// Использование
$authMiddleware = new AuthMiddleware([123456, 789012]);
$handler->onMessage($authMiddleware);
```

## Плагинная система
```php
// Интерфейс плагина
interface BotPlugin
{
    public function register(EventHandler $handler): void;
}

// Пример плагина
class EchoPlugin implements BotPlugin
{
    public function register(EventHandler $handler): void
    {
        $handler->command('echo', function($message) {
            return "Echo: " . $message->body->text;
        });
    }
}

// Регистрация плагинов
$bot = new MaxBot($config);
$handler = $bot->handler();

$plugins = [
    new EchoPlugin(),
    new HelpPlugin(),
    new AdminPlugin()
];

foreach ($plugins as $plugin) {
    $plugin->register($handler);
}
```

Обработка ошибок
```php
// Глобальный обработчик ошибок
set_exception_handler(function($exception) use ($bot) {
    error_log("Bot error: " . $exception->getMessage());
    
    // Отправка уведомления админу
    if (getenv('ADMIN_CHAT_ID')) {
        $bot->sendMessage([
            'text' => "❌ Ошибка бота: " . $exception->getMessage(),
            'chat_id' => getenv('ADMIN_CHAT_ID')
        ], []);
    }
});

// Обработка ошибок в middleware
$handler->onMessage(function($message, $next) {
    try {
        return $next($message);
    } catch (\Exception $e) {
        return "Произошла ошибка: " . $e->getMessage();
    }
});
```

## Обработка ошибок
### Типичные ошибки и их решение
"Invalid access token"

```php
// Проверьте правильность токена
$bot = new MaxBot([
    'access_token' => 'correct_token_here', // Убедитесь в правильности
    'secret' => 'your_secret'
]);
```

"Webhook already set"

```php
// Сброс вебхука
$bot->send('DELETE', 'subscriptions', []);
// Установка нового
$bot->setWebhook('https://new-url.com/webhook', $types);
```

"Message too long"

```php
// Разбивка длинных сообщений
function sendLongMessage($chatId, $text, $bot)
{
    $chunks = str_split($text, 4000);
    foreach ($chunks as $chunk) {
        $bot->sendMessage(['text' => $chunk, 'chat_id' => $chatId], []);
        sleep(1); // Задержка между сообщениями
    }
}
```

Логирование
```php
// Настройка логирования
class Logger
{
    public static function log($data, $level = 'INFO')
    {
        $logEntry = sprintf(
            "[%s] %s: %s\n",
            date('Y-m-d H:i:s'),
            $level,
            is_string($data) ? $data : json_encode($data, JSON_PRETTY_PRINT)
        );
        
        file_put_contents('bot.log', $logEntry, FILE_APPEND);
        
        // Также логируем в системный лог
        error_log($logEntry);
    }
}

// Использование
$handler->onMessage(function($message, $next) {
    Logger::log([
        'type' => 'message',
        'user_id' => $message->sender->user_id,
        'text' => $message->body->text
    ]);
    
    return $next($message);
});
```

## Миграция с других платформ
### Сравнение с Telegram Bot API

|Telegram API	|MAX API SDK	|Примечания|
|--|--|--|
|sendMessage	|$bot->sendMessage()	|Аналогичный метод|
|reply_markup	|->inlineKeyboard()	|Похожий синтаксис|
|InlineKeyboardButton	|MessageBuilder::callbackButton()	|Аналогичная функциональность|
|Location	|->location()	|Те же параметры|
|Contact	|->contact()	|Аналогичная структура|

Пример миграции с Telegram
```php
// Telegram код
$telegram = new Telegram('TELEGRAM_TOKEN');
$telegram->sendMessage([
    'chat_id' => $chatId,
    'text' => 'Hello',
    'reply_markup' => json_encode([
        'inline_keyboard' => [[
            ['text' => 'Button', 'callback_data' => 'action']
        ]]
    ])
]);

// MAX API SDK код
$bot = new MaxBot(['access_token' => 'MAX_TOKEN']);
$message = MessageBuilder::create('Hello')
    ->chatId($chatId)
    ->inlineKeyboard([[MessageBuilder::callbackButton('Button', 'action')]])
    ->build();

$bot->sendMessage($message, []);
```

## Заключение
MAX API SDK предоставляет мощный и удобный инструментарий для создания ботов для мессенджера MAX. Библиотека охватывает все основные возможности API и предоставляет дополнительные удобства в виде конструктора сообщений и обработчика событий.

### Рекомендации по использованию
- Используйте конструктор сообщений для создания сложных сообщений с вложениями
- Регистрируйте обработчики в порядке приоритета - от более специфичных к общим
- Используйте middleware для сквозной функциональности
- Всегда обрабатывайте ошибки и логируйте важные события
- Тестируйте бота перед развертыванием в продакшн

### Полезные ссылки
Официальная документация MAX API
Примеры ботов
Сообщество разработчиков

## Поддержка
При возникновении вопросов или обнаружении ошибок создавайте issue в репозитории проекта или обращайтесь в наше сообщество разработчиков.