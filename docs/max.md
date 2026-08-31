# Обзор

Модуль garmayev\max предоставляет интеграцию с API платформы MAX для Yii2-приложений. Он включает в себя полный набор классов для работы с сообщениями, вебхуками, загрузкой файлов, обработкой событий и типами данных платформы MAX.

## Содержание
- [Архитектура модуля](#архитектура-модуля)
- [Установка и настройка](#установка-и-настройка)
- [Основные компоненты](#основные-компоненты)
- [Работа с сообщениями](#работа-с-сообщениями)
- [Клавиатуры и кнопки](#клавиатуры-и-кнопки)
- [Загрузка файлов](#загрузка-файлов)
- [Вебхуки](#вебхуки)
- [Обработка событий](#обработка-событий)
- [Типы данных](#типы-данных)
- [Примеры использования](#примеры-использования)

### Архитектура модуля
```text
garmayev\max
├── base/
│   ├── MaxBase.php          # Базовый класс для работы с API
│   ├── Request.php          # Обработка входящих запросов
│   ├── Response.php         # Формирование ответов
│   └── VCardParser.php      # Парсер vCard контактов
├── types/
│   ├── Attachment.php       # Вложения
│   ├── Callback.php         # Callback события
│   ├── Chat.php             # Информация о чате
│   ├── Icon.php             # Иконки
│   ├── Link.php             # Ссылки
│   ├── Markup.php           # Разметка
│   ├── Message.php          # Сообщения
│   ├── MessageBody.php      # Тело сообщения
│   ├── Recipient.php        # Получатель
│   ├── Stat.php             # Статистика
│   ├── Subscription.php     # Подписки
│   ├── Update.php           # Обновления
│   ├── User.php             # Пользователи
│   ├── Response.php         # Ответы API
│   ├── buttons/             # Кнопки
│   │   ├── Button.php
│   │   ├── Callback.php
│   │   ├── Link.php
│   │   ├── Message.php
│   │   ├── OpenApp.php
│   │   ├── RequestContact.php
│   │   └── RequestGeoLocation.php
│   └── payloads/            # Полезные нагрузки
│       ├── Payload.php
│       ├── ContactPayload.php
│       ├── ImagePayload.php
│       ├── InlineKeyboardPayload.php
│       ├── LocationPayload.php
│       ├── MediaPayload.php
│       └── StickerPayload.php
├── Max.php                  # Основной класс
├── EventHandler.php         # Обработчик событий
└── Bootstrap.php            # Регистрация компонента
```

### Установка и настройка
1. Регистрация модуля
   В конфигурации приложения добавьте компонент:

```php
// config/main.php
return [
   'components' => [
       'max' => [
           'class' => 'garmayev\max\components\MaxComponent',
           'access_token' => 'YOUR_ACCESS_TOKEN',
           'secret' => 'YOUR_SECRET_KEY',
       ],
   ],
];
```
2. Инициализация бота
```php
   $bot = new \garmayev\max\Max([
      'access_token' => 'your_token',
      'secret' => 'your_secret',
   ]);

    $bot = \Yii::$app->max;
```

### Основные компоненты
#### Класс Max
   Главный класс для взаимодействия с API MAX.

_Свойства:_
- access_token - Токен доступа
- secret - Секретный ключ

*Методы:*

`__construct(array $config)`
Конструктор класса.

*Параметры:*

`$config` - Массив с настройками (access_token, secret)

*Пример:*

```php
$bot = new Max([
   'access_token' => 'xxx',
   'secret' => 'yyy'
]);
```

`handler(): EventHandler`

Инициализирует обработчик событий.

*Пример:*

```php
$bot->handler()
   ->onMessage(function($update) {
   // Обработка сообщений
   })
   ->run();
```

### Работа с сообщениями
#### Отправка сообщения
```php
// Простое сообщение пользователю
$response = $bot->sendMessage(
   ['text' => 'Привет, мир!'],
   ['user_id' => 123456]
);

// Сообщение в чат
$response = $bot->sendMessage(
   [
      'text' => 'Сообщение в чат',
      'attachments' => [...] // вложения
   ],
   ['chat_id' => 789012, 'chat_type' => 'chat']
);
```

#### Редактирование сообщения
```php
$response = $bot->editMessage(
   ['text' => 'Новый текст'],
   ['message_id' => 'mid_123']
);
```

#### Удаление сообщения
```php
$response = $bot->deleteMessage(['message_id' => 'mid_123']);
```

#### Ответ на callback
```php
$response = $bot->sendAnswer(
   ['text' => 'Ответ на callback'],
   ['callback_id' => 'cb_123']
);
```

#### Быстрый ответ на callback
```php
$bot->sendCallbackAnswer(['callback_id' => 'cb_123']);
```

### Клавиатуры и кнопки
Типы кнопок

|Тип	|Константа	|Описание |
|--|--|--|
|Callback	|Button::TYPE_CALLBACK	|Отправляет callback запрос |
|Link	|Button::TYPE_LINK	|Открывает URL|
|Message	|Button::TYPE_MESSAGE	|Отправляет сообщение|
|OpenApp	|Button::TYPE_OPEN_APP	|Открывает мини-приложение|
|RequestContact	|Button::TYPE_REQUEST_CONTACT	|Запрашивает контакт|
|RequestGeoLocation	|Button::TYPE_REQUEST_GEO_LOCATION	|Запрашивает геолокацию|

### Создание клавиатуры
```php
use garmayev\max\types\Attachment;
use garmayev\max\types\buttons\Callback;
use garmayev\max\types\buttons\Link;

$keyboard = [
   'type' => 'inline_keyboard',
   'payload' => [
      'buttons' => [
         [
            new Callback([
               'text' => 'Кнопка 1',
               'payload' => 'action_1'
            ]),
            new Link([
               'text' => 'Ссылка',
               'url' => 'https://example.com'
            ])
         ],
         [
            new Callback([
               'text' => 'Кнопка 2',
               'payload' => 'action_2',
               'intent' => 'positive' // Опционально
            ])
         ]
      ]
   ]
];

// Отправка с клавиатурой
$response = $bot->sendMessage([
   'text' => 'Выберите действие:',
   'attachments' => [
      [
         'type' => Attachment::TYPE_INLINE_KEYBOARD,
         'payload' => $keyboard['payload']
      ]
   ]
], ['user_id' => 123456]);
```

#### Кнопка Callback
```php
use garmayev\max\types\buttons\Callback;

$button = new Callback([
   'text' => 'Нажать',
   'payload' => 'action_id',
   'intent' => 'positive' // positive, negative, default
]);
```

#### Кнопка Link
```php
use garmayev\max\types\buttons\Link;

$button = new Link([
   'text' => 'Перейти',
   'url' => 'https://example.com'
]);
```

#### Кнопка Message
```php
use garmayev\max\types\buttons\Message;

$button = new Message([
   'text' => 'Отправить "Привет"'
]);
```

#### Кнопка OpenApp
```php
use garmayev\max\types\buttons\OpenApp;

$button = new OpenApp([
   'text' => 'Открыть приложение',
   'web_app' => '@my_bot',
   'user_id' => 123456,
   'payload' => 'start_param'
]);
```

#### Кнопка RequestContact
```php
use garmayev\max\types\buttons\RequestContact;

$button = new RequestContact([
   'text' => 'Поделиться контактом'
]);
```

#### Кнопка RequestGeoLocation
```php
use garmayev\max\types\buttons\RequestGeoLocation;

$button = new RequestGeoLocation([
   'text' => 'Поделиться локацией',
   'quick' => true // Отправить без подтверждения
]);
```

#### Загрузка файлов
Получение URL для загрузки
```php
// Получение URL для загрузки изображения
$response = $bot->getUploadUrl('image');
$uploadUrl = $response->getUrl();

// Получение URL для загрузки файла
$response = $bot->getUploadUrl('file');
```

Загрузка файла
```php
// Загрузка файла по полученному URL
$result = $bot->uploadFile($uploadUrl, '/path/to/file.jpg');
$token = $result['token'];
```

Полный цикл загрузки
```php
// Автоматическое получение URL и загрузка
$result = $bot->upload('/path/to/image.jpg', 'image');
$token = $result['token'];

// С ожиданием обработки для видео/аудио
$result = $bot->upload('/path/to/video.mp4', 'video', true);
```

#### Создание вложения
```php
$attachment = $bot->createAttachment($token, 'image');
```

#### Отправка сообщения с вложением
```php
// Упрощенный метод
$response = $bot->sendMessageWithAttachment(
   123456,                 // ID получателя
   'Смотрите фото!',      // Текст
   $token,                // Токен файла
   'image',               // Тип файла
   'user'                 // Тип чата
);

// Ручной способ
$attachment = [
   'type' => 'image',
   'payload' => ['token' => $token]
];

$response = $bot->sendMessage([
   'text' => 'Смотрите фото!',
   'attachments' => [$attachment]
], ['user_id' => 123456]);
```

#### Вебхуки
Установка вебхука
```php
$response = $bot->setWebhook('https://example.com/webhook', [
   'message_created',
   'message_callback',
   'message_edited'
]);
```

#### Получение вебхуков
```php
$webhooks = $bot->getWebhooks();
$subscriptions = $webhooks->getSubscriptions();
```

#### Удаление вебхука
```php
$response = $bot->deleteWebhook('https://example.com/webhook');
```

### Обработка событий
#### Инициализация обработчика
```php
$bot->handler()
    ->onMessage(function($update) {
        $message = $update->getMessage();
        $text = $message->getBody()->getText();
        $user = $message->getSender();
        
        // Ответное сообщение
        $this->bot->sendMessage(
            ['text' => "Вы сказали: $text"],
            ['user_id' => $user->getUser_id()]
        );
    })->onCallback(function($update) {
        $callback = $update->getCallback();
        $payload = $callback->getPayload();
      
        // Обработка callback
        $this->bot->sendCallbackAnswer([
            'callback_id' => $callback->getCallback_id()
        ]);
    })->onBotAdded(function($update) {
        // Бот добавлен в чат
    })
    ->onBotRemoved(function($update) {
        // Бот удален из чата
    })
    ->run();
```

#### Доступные события
|Метод	|Событие	|Константа |
|--|--|--|
|onMessage()	|Создание сообщения	|Update::TYPE_MESSAGE_CREATED|
|onCallback()	|Callback запрос	|Update::TYPE_MESSAGE_CALLBACK|
|onMessageEdited()	|Редактирование сообщения	|Update::TYPE_MESSAGE_EDITED|
|onMessageRemoved()	|Удаление сообщения	|Update::TYPE_MESSAGE_REMOVED|
|onBotAdded()	|Добавление бота	|Update::TYPE_BOT_ADDED|
|onBotRemoved()	|Удаление бота	|Update::TYPE_BOT_REMOVED|
|onDialogMuted()	|Диалог заглушен	|Update::TYPE_DIALOG_MUTED|
|onDialogUnmuted()	|Диалог разглушен	|Update::TYPE_DIALOG_UNMUTED|
|onUserAdded()	|Добавлен пользователь	|Update::TYPE_USER_ADDED|
|onUserRemoved()	|Удален пользователь	|Update::TYPE_USER_REMOVED|

#### Получение данных из события
```php
$bot->handler()->onMessage(function($update) {
   // Тип обновления
   $type = $update->getUpdate_type();

    // Временная метка
    $timestamp = $update->getTimestamp();
    
    // Локаль пользователя
    $locale = $update->getUser_locale();
    
    // Сообщение
    $message = $update->getMessage();
    
    // Отправитель
    $sender = $message->getSender();
    $senderId = $sender->getUser_id();
    $senderName = $sender->getDisplayName();
    
    // Текст сообщения
    $text = $message->getBody()->getText();
    
    // Вложения
    $attachments = $message->getBody()->getAttachments();
    
    // Получатель
    $recipient = $message->getRecipient();
    $chatId = $recipient->getChat_id();
    $chatType = $recipient->getChat_type();
});
```

## Типы данных
## User
Информация о пользователе.

*Методы:*

`getUser_id(): ?int` - ID пользователя

`getFirst_name(): ?string` - Имя

`getLast_name(): ?string` - Фамилия

`getName(): ?string` - Полное имя

`getUsername(): ?string` - Имя пользователя

`isIs_bot(): ?bool` - Является ли ботом

`getDisplayName(): string` - Отображаемое имя

*Пример:*

```php
$user = new User([
   'user_id' => 123,
   'first_name' => 'Иван',
   'last_name' => 'Петров',
   'username' => 'ivan_p'
]);

echo $user->getDisplayName(); // "Иван Петров"
```

### Message
Объект сообщения.

*Методы:*

`getSender(): User` - Отправитель

`getRecipient(): Recipient` - Получатель

`getTimestamp(): int` - Временная метка

`getLink(): Link` - Ссылка (reply/forward)

`getBody(): MessageBody` - Тело сообщения

`getStat(): Stat` - Статистика

`getUrl(): string` - URL сообщения

### MessageBody
Тело сообщения.

*Методы:*

`getMid(): string` - ID сообщения

`getSeq(): int` - Порядковый номер

`getText(): string` - Текст

`getAttachments(): array` - Вложения

`getMarkup(): array` - Разметка

### Attachment
Вложение.

*Методы:*

`getType(): string` - Тип вложения

`getPayload(): Payload` - Полезная нагрузка

`getFilename(): string` - Имя файла

`getSize(): int` - Размер

`getLatitude(): float` - Широта (для локации)

`getLongitude(): float` - Долгота (для локации)

### Типы вложений:

```php
Attachment::TYPE_LOCATION      // Геолокация
Attachment::TYPE_CONTACT       // Контакт
Attachment::TYPE_IMAGE         // Изображение
Attachment::TYPE_STICKER       // Стикер
Attachment::TYPE_FILE          // Файл
Attachment::TYPE_INLINE_KEYBOARD // Клавиатура
```

### Response
Ответ от API.

*Методы:*

`isSuccess(): bool` - Успешен ли запрос

`getData(): ?array` - Данные ответа

`getError(): ?string` - Текст ошибки

`getMessageId(): ?string` - ID сообщения

`getMessageText(): ?string` - Текст сообщения

`getChatId(): ?int` - ID чата

`getChatType(): ?string` - Тип чата

`getStatus(): ?string` - Статус

`getSubscriptions(): ?array` - Подписки

`getUrl(): ?string` - URL (для загрузки)

`getToken(): ?string` - Токен (для загрузки)

### VCardParser
Парсер контактной информации в формате vCard.

```php
$parser = new VCardParser();
if ($parser->parse($vcardString)) {
   $data = $parser->toArray();
   $fullName = $parser->getFullName();
   $phones = $parser->getPhones();
   $emails = $parser->getEmails();
   $name = $parser->getStructuredName();
   $organization = $parser->getOrganization();
}

// Быстрый парсинг
$data = VCardParser::parseQuick($vcardString);
```

## Примеры использования
### Полный пример бота
```php
<?php

namespace app\controllers;

use garmayev\max\Max;
use garmayev\max\types\Attachment;
use garmayev\max\types\buttons\Callback;
use garmayev\max\types\buttons\Link;
use yii\web\Controller;

class BotController extends Controller
{
    private Max $bot;
    
    public function init()
    {
        parent::init();
        $this->bot = new Max([
            'access_token' => 'YOUR_TOKEN',
            'secret' => 'YOUR_SECRET'
        ]);
    }
    
    public function actionWebhook()
    {
        $this->bot->handler()
            ->onMessage(function($update) {
                $message = $update->getMessage();
                $user = $message->getSender();
                $text = $message->getBody()->getText();
                
                switch ($text) {
                    case '/start':
                        $this->handleStart($user);
                        break;
                    case '/help':
                        $this->handleHelp($user);
                        break;
                    default:
                        $this->handleUnknown($user);
                }
            })
            ->onCallback(function($update) {
                $callback = $update->getCallback();
                $payload = $callback->getPayload();
                $user = $callback->getUser();
                
                $this->handleCallback($user, $payload);
            })
            ->run();
    }
    
    private function handleStart($user)
    {
        $keyboard = [
            'type' => Attachment::TYPE_INLINE_KEYBOARD,
            'payload' => [
                'buttons' => [
                    [
                        new Callback([
                            'text' => 'Получить информацию',
                            'payload' => 'get_info'
                        ])
                    ],
                    [
                        new Link([
                            'text' => 'Сайт',
                            'url' => 'https://example.com'
                        ])
                    ]
                ]
            ]
        ];
        
        $this->bot->sendMessage([
            'text' => "Привет, {$user->getDisplayName()}!",
            'attachments' => [$keyboard]
        ], ['user_id' => $user->getUser_id()]);
    }
    
    private function handleHelp($user)
    {
        $this->bot->sendMessage([
            'text' => "Доступные команды:\n/start - Начать\n/help - Помощь"
        ], ['user_id' => $user->getUser_id()]);
    }
    
    private function handleCallback($user, $payload)
    {
        switch ($payload) {
            case 'get_info':
                $this->bot->sendCallbackAnswer([
                    'callback_id' => $_GET['callback_id'] ?? ''
                ]);
                $this->bot->sendMessage([
                    'text' => "Ваш ID: {$user->getUser_id()}"
                ], ['user_id' => $user->getUser_id()]);
                break;
        }
    }
}
```

### Отправка фотографии
```php
// Загрузка фото
$result = $this->bot->upload('/path/to/photo.jpg', 'image');
$token = $result['token'];

// Отправка с фото
$this->bot->sendMessageWithAttachment(
    $userId,
    'Ваше фото',
    $token,
    'image'
);
```

### Отправка геолокации
```php
use garmayev\max\types\Attachment;

$location = [
    'type' => Attachment::TYPE_LOCATION,
    'payload' => [
        'latitude' => 55.7558,
        'longitude' => 37.6173
    ]
];

$this->bot->sendMessage([
    'text' => 'Моя локация',
    'attachments' => [$location]
], ['user_id' => $userId]);
```

### Получение информации о пользователе
```php
$response = $this->bot->getUserInfo(['user_id' => 123456]);

if ($response->isSuccess()) {
    $data = $response->getData();
    $user = new User($data);
    echo "Имя: " . $user->getDisplayName();
}
```

### Получение информации о чате
```php
$response = $this->bot->getChatInfo(['chat_id' => 789012]);

if ($response->isSuccess()) {
    $data = $response->getData();
    $chat = new Chat($data);
    echo "Название чата: " . $chat->getTitle();
    echo "Участников: " . $chat->getParticipants_count();
}
```

### Обработка контакта
```php
$bot->handler()->onMessage(function($update) {
    $attachments = $update->getMessage()->getBody()->getAttachments();
    
    foreach ($attachments as $attachment) {
        if ($attachment->getType() === Attachment::TYPE_CONTACT) {
            $payload = $attachment->getPayload();
            
            // vCard информация
            $vcfInfo = $payload->getVcf_info();
            $name = $vcfInfo['full_name'] ?? 'Без имени';
            $phones = $vcfInfo['phones'] ?? [];
            
            // Номер телефона из vCard
            $phone = $payload->getVcf_phone();
            
            // Информация из MAX
            $maxInfo = $payload->getMax_info();
        }
    }
});
```

### Обработка локации
```php
$bot->handler()->onMessage(function($update) {
    $attachments = $update->getMessage()->getBody()->getAttachments();
    
    foreach ($attachments as $attachment) {
        if ($attachment->getType() === Attachment::TYPE_LOCATION) {
            $lat = $attachment->getLatitude();
            $lng = $attachment->getLongitude();
            
            // Обработка координат
        }
    }
});
```

### Обработка ошибок
```php
try {
    $response = $this->bot->sendMessage(
        ['text' => 'Тест'],
        ['user_id' => 123456]
    );
    
    if (!$response->isSuccess()) {
        Yii::error('Ошибка отправки: ' . $response->getError());
    }
} catch (\GuzzleHttp\Exception\GuzzleException $e) {
    Yii::error('Ошибка API: ' . $e->getMessage());
}
```

## Заключение
Модуль garmayev\max предоставляет полный и удобный интерфейс для работы с API платформы MAX в Yii2-приложениях. Он включает все необходимые компоненты для создания ботов, обработки событий, работы с файлами и управления вебхуками.