# Документация для MAX PHP SDK
## Оглавление

- [Класс Max](#класс-max)
- [Класс EventHandler](#класс-eventhandler)
- [Класс MessageBuilder](#класс-messagebuilder)

## Примеры использования

### Класс Max
Основной класс для работы с MAX API. Наследуется от MaxBase и предоставляет удобные методы для взаимодействия с API.

#### Свойства
|Свойство	|Тип	|Описание|
|---|---|---|
|$access_token	|string	|Токен доступа бота|
|$secret	|string	|Секретный ключ для вебхуков|
|$handler	|EventHandler	|Обработчик событий|

#### Методы
`__construct(array $config)`
Конструктор класса.

Параметры:

$config (array) - конфигурация бота (должна содержать access_token и secret)

Пример:

```php
$bot = new Max([
    'access_token' => 'ВАШ_ТОКЕН',
    'secret' => 'ВАШ_СЕКРЕТ'
]);
```

`handler(): EventHandler`
Инициализирует и возвращает обработчик событий.

Возвращает:

EventHandler - экземпляр обработчика событий

Пример:

```php
$bot->handler()
    ->onMessage(function($message) {
        // Обработка сообщений
    })
    ->handle();
```

`setWebhook(string $url, array $types): Response`
Устанавливает вебхук для получения обновлений.

Параметры:

$url (string) - URL для получения вебхуков

$types (array) - массив типов событий для подписки

Возвращает:

Response - ответ от API

Пример:

php
$response = $bot->setWebhook('https://example.com/webhook', [
    Update::TYPE_MESSAGE_CREATED,
    Update::TYPE_MESSAGE_CALLBACK
]);
deleteWebhook(): Response
Удаляет установленный вебхук.

Возвращает:

Response - ответ от API

Пример:

php
$response = $bot->deleteWebhook();
sendMessage(array $params, array $args = []): Response
Отправляет сообщение.

Параметры:

$params (array) - параметры сообщения

$args (array) - query параметры (user_id, chat_id, chat_type)

Возвращает:

Response - ответ от API

Пример:

php
$response = $bot->sendMessage([
    'text' => 'Привет, мир!',
    'chat_id' => 123456
]);
editMessage(array $params, array $args = []): Response
Редактирует существующее сообщение.

Параметры:

$params (array) - новые параметры сообщения

$args (array) - query параметры (обязательно message_id)

Пример:

php
$response = $bot->editMessage([
    'text' => 'Обновленный текст'
], ['message_id' => 789]);
deleteMessage(array $args = []): Response
Удаляет сообщение.

Параметры:

$args (array) - query параметры (обязательно message_id)

Пример:

php
$response = $bot->deleteMessage(['message_id' => 789]);
sendAnswer(array $params, array $args = []): Response
Отправляет ответ на callback-запрос.

Параметры:

$params (array) - параметры ответа

$args (array) - query параметры (обязательно callback_id)

Пример:

php
$response = $bot->sendAnswer([
    'text' => 'Ответ на callback'
], ['callback_id' => 'abc123']);
getUserInfo(array $args = []): Response
Получает информацию о пользователе.

Параметры:

$args (array) - query параметры (обязательно user_id)

Пример:

php
$response = $bot->getUserInfo(['user_id' => 12345]);
getChatInfo(array $args = []): Response
Получает информацию о чате.

Параметры:

$args (array) - query параметры (обязательно chat_id)

Пример:

php
$response = $bot->getChatInfo(['chat_id' => 123456]);
sendCallbackAnswer(array $args = []): Response
Отправляет уведомление о выполнении действия (callback).

Параметры:

$args (array) - query параметры (обязательно callback_id)

Пример:

php
$response = $bot->sendCallbackAnswer(['callback_id' => 'abc123']);

### Класс EventHandler
Обработчик событий для MAX API. Позволяет регистрировать обработчики для различных типов событий.

Методы
__construct(MaxBase $bot)
Конструктор класса.

Параметры:

$bot (MaxBase) - экземпляр бота

init(): self
Инициализирует обработчик, парсит входящий запрос.

Возвращает:

self - текущий экземпляр для цепочки вызовов

on(string $eventType, callable $handler): self
Регистрирует обработчик для определенного типа события.

Параметры:

$eventType (string) - тип события (см. константы Update::TYPE_*)

$handler (callable) - функция-обработчик

Пример:

php
$handler->on(Update::TYPE_MESSAGE_CREATED, function(Request $request) {
    $message = $request->getMessage();
    // Обработка сообщения
});
onMessage(callable $handler): self
Регистрирует обработчик для текстовых сообщений.

Пример:

php
$handler->onMessage(function(Message $message) {
    echo "Получено сообщение: " . $message->body->text;
});
onCallback(callable $handler): self
Регистрирует обработчик для нажатия кнопок (callback).

Пример:

php
$handler->onCallback(function(Callback $callback) {
    echo "Нажата кнопка с payload: " . $callback->getPayload();
});
command(string $command, callable $handler): self
Регистрирует обработчик для текстовых команд.

Параметры:

$command (string) - команда (без символа / или !)

$handler (callable) - функция-обработчик

Пример:

php
$handler->command('start', function(Message $message, array $args) {
    // Обработка команды /start или !start
});
callback(string $payload, callable $handler): self
Регистрирует обработчик для callback с определенным payload.

Параметры:

$payload (string) - значение payload

$handler (callable) - функция-обработчик

Пример:

php
$handler->callback('approve', function(Callback $callback) {
    // Обработка нажатия кнопки с payload='approve'
});
contains(string $text, callable $handler): self
Регистрирует обработчик для текста, содержащего определенную подстроку.

Пример:

php
$handler->contains('привет', function(Message $message) {
    // Обработка сообщений, содержащих "привет"
});
regex(string $pattern, callable $handler): self
Регистрирует обработчик для текста, соответствующего регулярному выражению.

Пример:

php
$handler->regex('/^[0-9]+$/', function(Message $message, array $matches) {
    // Обработка сообщений, содержащих только цифры
});
default(callable $handler): self
Регистрирует обработчик по умолчанию.

Пример:

php
$handler->default(function(Request $request) {
    // Обработка всех необработанных событий
});
handle(): bool
Обрабатывает входящее событие.

Возвращает:

bool - успешно ли обработано событие

Пример:

php
$handled = $handler->handle();
if ($handled) {
    echo "Событие обработано";
}
getRequest(): ?Request
Возвращает текущий запрос.

getMessage(): ?Message
Возвращает сообщение из запроса.

getCallback(): ?Callback
Возвращает callback из запроса.

getChat(): ?Chat
Возвращает чат из запроса.

getUser(): ?User
Возвращает пользователя из запроса.

### Класс MessageBuilder
Конструктор сообщений для MAX API. Позволяет удобно создавать структурированные сообщения с различными вложениями.

Статические методы
create(?string $text = null, array $args = []): self
Создает новый экземпляр конструктора сообщений.

Параметры:

$text (string|null) - текст сообщения

$args (array) - дополнительные аргументы (chat_id, chat_type, user_id)

Пример:

php
$builder = MessageBuilder::create('Привет!', ['chat_id' => 123456]);
Методы экземпляра
text(string $text): self
Устанавливает текст сообщения.

chatId(int $chatId): self
Устанавливает chat_id.

chatType(string $chatType): self
Устанавливает chat_type.

userId(int $userId): self
Устанавливает user_id.

inlineKeyboard(array $buttons, bool $isOneTime = false): self
Добавляет встроенную клавиатуру.

Пример:

php
$builder->inlineKeyboard([
    [
        MessageBuilder::callbackButton('Кнопка 1', 'action_1'),
        MessageBuilder::callbackButton('Кнопка 2', 'action_2')
    ]
]);
image(string $token, ?string $filename = null, ?int $width = null, ?int $height = null): self
Добавляет изображение.

sticker(string $code): self
Добавляет стикер.

file(string $token, string $filename, int $size): self
Добавляет файл.

location(float $latitude, float $longitude): self
Добавляет местоположение.

contact(string $name, int $contactId, ?string $vcfInfo = null, ?string $vcfPhone = null): self
Добавляет контакт.

build(): array
Формирует итоговое сообщение для отправки.

Статические методы для создания кнопок
callbackButton(string $text, string $payload, ?string $intent = null): array
Создает кнопку callback.

linkButton(string $text, string $url): array
Создает кнопку ссылки.

messageButton(string $text): array
Создает кнопку сообщения.

requestContactButton(string $text): array
Создает кнопку запроса контакта.

requestLocationButton(string $text, bool $quick = false): array
Создает кнопку запроса геолокации.

openAppButton(string $text, string $webApp, ?int $userId = null, ?string $payload = null): array
Создает кнопку открытия мини-приложения.

Статические методы для создания клавиатур
row(array $buttons): array
Создает строку кнопок для клавиатуры.

keyboard(array $rows): array
Создает клавиатуру с несколькими строками.

Быстрые методы
textOnly(string $text, int $chatId): array
Создает простое текстовое сообщение.

withKeyboard(string $text, int $chatId, array $keyboard, bool $isOneTime = false): array
Создает сообщение с клавиатурой.

withImage(string $text, int $chatId, string $imageToken): array
Создает сообщение с изображением.

Примеры использования
Пример 1: Простой бот с обработкой команд
php
<?php

use garmayev\max\Max;
use garmayev\max\types\Update;

$bot = new Max([
    'access_token' => 'ВАШ_ТОКЕН',
    'secret' => 'ВАШ_СЕКРЕТ'
]);

// Установка вебхука (выполняется один раз)
// $bot->setWebhook('https://example.com/webhook', [Update::TYPE_MESSAGE_CREATED]);

// Обработка входящих сообщений
$bot->handler()
    ->command('start', function($message, $args) use ($bot) {
        // Отправка приветственного сообщения
        $response = $bot->sendMessage([
            'text' => 'Привет! Я ваш бот.',
            'chat_id' => $message->getRecipient()->getChat_id()
        ]);
    })

    ->command('help', function($message, $args) use ($bot) {
        $response = $bot->sendMessage([
            'text' => 'Доступные команды: /start, /help, /menu',
            'chat_id' => $message->getRecipient()->getChat_id()
        ]);
    })

    ->command('menu', function($message, $args) use ($bot) {
        // Создание сообщения с клавиатурой
        $messageData = MessageBuilder::withKeyboard(
            'Выберите действие:',
            $message->getRecipient()->getChat_id(),
            [
                [
                    MessageBuilder::callbackButton('📞 Контакты', 'show_contacts'),
                    MessageBuilder::callbackButton('📍 Локация', 'request_location')
                ],
                [
                    MessageBuilder::linkButton('🌐 Сайт', 'https://example.com')
                ]
            ]
        );

        $response = $bot->sendMessage($messageData);
    })

    ->callback('show_contacts', function($callback) use ($bot) {
        // Ответ на callback
        $bot->sendAnswer([
            'text' => 'Наши контакты: +7 (XXX) XXX-XX-XX'
        ], ['callback_id' => $callback->callback_id]);

        // Отправка уведомления
        $bot->sendCallbackAnswer(['callback_id' => $callback->callback_id]);
    })

    ->contains('привет', function($message) use ($bot) {
        $bot->sendMessage([
            'text' => 'И тебе привет! 👋',
            'chat_id' => $message->getRecipient()->getChat_id()
        ]);
    })

    ->default(function($request) use ($bot) {
        // Обработка всех остальных сообщений
        $message = $request->getMessage();
        if ($message) {
            $bot->sendMessage([
                'text' => 'Я получил ваше сообщение: "' . ($message->body->text ?? '') . '"',
                'chat_id' => $message->getRecipient()->getChat_id()
            ]);
        }
    })

    ->handle();
Пример 2: Создание сложного сообщения с различными вложениями
php
<?php

use garmayev\max\Max;
use garmayev\max\MessageBuilder;

$bot = new Max([
    'access_token' => 'ВАШ_ТОКЕН',
    'secret' => 'ВАШ_СЕКРЕТ'
]);

// Создание комплексного сообщения
$message = MessageBuilder::create('Ваш заказ готов!')
    ->chatId(123456)
    ->chatType('private')
    ->image('image_token_123', 'product.jpg', 800, 600)
    ->inlineKeyboard([
        [
            MessageBuilder::callbackButton('✅ Принять', 'order_accept', 'primary'),
            MessageBuilder::callbackButton('❌ Отклонить', 'order_reject', 'danger')
        ],
        [
            MessageBuilder::linkButton('📄 Подробности', 'https://example.com/order/123')
        ]
    ])
    ->location(55.7558, 37.6173) // Координаты Москвы
    ->build();

// Отправка сообщения
$response = $bot->sendMessage($message);
Пример 3: Использование быстрых методов MessageBuilder
php
<?php

// Простое текстовое сообщение
$textMessage = MessageBuilder::textOnly('Привет!', 123456);

// Сообщение с клавиатурой
$keyboardMessage = MessageBuilder::withKeyboard(
    'Выберите действие:',
    123456,
    [
        [
            MessageBuilder::callbackButton('Опция 1', 'opt1'),
            MessageBuilder::callbackButton('Опция 2', 'opt2')
        ]
    ],
    true // одноразовая клавиатура
);

// Сообщение с изображением
$imageMessage = MessageBuilder::withImage(
    'Посмотрите наше новое изображение!',
    123456,
    'image_token_456'
);
Пример 4: Получение информации о пользователе и чате
php
<?php

$bot = new Max([
    'access_token' => 'ВАШ_ТОКЕН',
    'secret' => 'ВАШ_СЕКРЕТ'
]);

// Получение информации о пользователе
$userInfo = $bot->getUserInfo(['user_id' => 12345]);
if ($userInfo->ok) {
    echo "Имя пользователя: " . $userInfo->result->first_name;
}

// Получение информации о чате
$chatInfo = $bot->getChatInfo(['chat_id' => 123456]);
if ($chatInfo->ok) {
    echo "Название чата: " . $chatInfo->result->title;
}
Пример 5: Редактирование и удаление сообщений
php
<?php

$bot = new Max([
    'access_token' => 'ВАШ_ТОКЕН',
    'secret' => 'ВАШ_СЕКРЕТ'
]);

// Отправка сообщения
$response = $bot->sendMessage([
    'text' => 'Это временное сообщение',
    'chat_id' => 123456
]);

if ($response->ok) {
    $messageId = $response->result->message_id;

    // Редактирование сообщения через 5 секунд
    sleep(5);
    $bot->editMessage([
        'text' => 'Сообщение обновлено!'
    ], ['message_id' => $messageId]);

    // Удаление сообщения через 10 секунд
    sleep(10);
    $bot->deleteMessage(['message_id' => $messageId]);
}
Пример 6: Обработка различных типов событий
php
<?php

use garmayev\max\Max;
use garmayev\max\types\Update;

$bot = new Max([
    'access_token' => 'ВАШ_ТОКЕН',
    'secret' => 'ВАШ_СЕКРЕТ'
]);

$bot->handler()
    ->onMessageEdited(function($message) {
        echo "Сообщение отредактировано: " . $message->body->text;
    })

    ->onBotAdded(function($request) use ($bot) {
        $chat = $request->getChat();
        $bot->sendMessage([
            'text' => 'Спасибо за добавление в чат!',
            'chat_id' => $chat->getChat_id()
        ]);
    })

    ->onUserAdded(function($request) use ($bot) {
        $user = $request->getUser();
        $chat = $request->getChat();

        $bot->sendMessage([
            'text' => "Добро пожаловать, {$user->first_name}!",
            'chat_id' => $chat->getChat_id()
        ]);
    })

    ->handle();
Эта документация охватывает все основные возможности классов MAX PHP SDK и предоставляет готовые примеры для быстрого начала работы.