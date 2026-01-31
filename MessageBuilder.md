```php
use garmayev\max\MessageBuilder;

// 1. Простое текстовое сообщение
$message1 = MessageBuilder::textOnly('Привет! Как дела?', 123456);

// 2. Сообщение с клавиатурой
$message2 = MessageBuilder::create('Выберите действие:')
    ->chatId(123456)
    ->inlineKeyboard([
        MessageBuilder::row([
            MessageBuilder::callbackButton('Кнопка 1', 'action_1', 'primary'),
            MessageBuilder::callbackButton('Кнопка 2', 'action_2')
        ]),
        MessageBuilder::row([
            MessageBuilder::linkButton('Сайт', 'https://example.com'),
            MessageBuilder::messageButton('Отправить текст')
        ])
    ])
    ->build();
```

```php
use garmayev\max\MessageBuilder;

// 3. Сообщение с изображением
$message3 = MessageBuilder::create('Вот ваше изображение:')
    ->chatId(123456)
    ->image('image_token_123', 'photo.jpg', 800, 600)
    ->build();

// 4. Сообщение с несколькими вложениями
$message4 = MessageBuilder::create('Разная информация:')
    ->chatId(123456)
    ->location(55.7558, 37.6173) // Координаты Москвы
    ->contact('Иван Иванов', 789, 'BEGIN:VCARD...', '+79991234567')
    ->sticker('happy_sticker_001')
    ->build();
```


```php
// 5. Быстрый метод с клавиатурой
$keyboard = MessageBuilder::keyboard([
    MessageBuilder::row([
        MessageBuilder::callbackButton('Да', 'yes'),
        MessageBuilder::callbackButton('Нет', 'no')
    ])
]);

$message5 = MessageBuilder::withKeyboard('Вы согласны?', 123456, $keyboard, true);

// 6. Использование в классе Max для отправки
$max = new \garmayev\max\Max([
    'access_token' => 'your_token',
    'secret' => 'your_secret'
]);
```

```php
// Отправка сообщения с конструктором
$message = MessageBuilder::create('Привет!')
    ->chatId(123456)
    ->inlineKeyboard([
        [MessageBuilder::callbackButton('Нажми меня', 'test')]
    ])
    ->build();

$response = $max->sendMessage($message, []);
```