# Обзор
MessageBuilder - это строитель (builder) сообщений для платформы MAX. Он предоставляет удобный и гибкий способ создания сложных сообщений с различными вложениями, клавиатурами и кнопками. Класс реализует паттерн "Строитель" (Builder Pattern) для пошагового конструирования сообщений.

`Пространство имен:` garmayev\max
`Наследование:` Нет

# Содержание
- [Архитектура и принцип работы](#архитектура-и-принцип-работы)
- [Основные методы](#основные-методы)
  - [Создание экземпляра](#создание-экземпляра)
  - [Настройка сообщения](#настройка-сообщения) 
  - [Добавление вложений](#добавление-вложений) 
  - [Создание кнопок](#создание-кнопок) 
  - [Вспомогательные методы](#вспомогательные-методы) 
- [Типы вложений и кнопок](#типы-вложений-и-кнопок)
- [Примеры использования](#примеры-использования)
- [Практические сценарии](#практические-сценарии)

# Архитектура и принцип работы
`MessageBuilder` использует паттерн "Строитель" (Builder), который позволяет создавать сложные объекты пошагово. Каждый метод возвращает экземпляр самого себя ($this), что обеспечивает цепочку вызовов (fluent interface).

![MessageBuilderGraph](./assets/builder.svg)

### Преимущества использования:

- Удобный цепочечный синтаксис
- Автоматическая загрузка файлов по URL
- Предварительно созданные типы кнопок
- Гибкость в создании сложных сообщений

# Основные методы
## Создание экземпляра

`static create(?string $text = null, array $args = []): self`

Создает новый экземпляр конструктора сообщений.

**Параметры:**

`$text (string|null)` - Текст сообщения (опционально)

`$args (array)` - Дополнительные параметры (chat_id, chat_type, user_id)

**Возвращает:** MessageBuilder - экземпляр строителя

**Пример:**

```php
// Создание с текстом
$builder = MessageBuilder::create('Привет!');

// Создание с параметрами получателя
$builder = MessageBuilder::create(null, [
    'user_id' => 123456,
    'chat_type' => 'user'
]);
```

## Настройка сообщения
### text(string $text): self

Устанавливает текст сообщения.

**Параметры:**

`$text (string)` - Текст сообщения

**Возвращает:** self - для цепочки вызовов

**Пример:**

```php
$builder->text('Новое сообщение');
```

### format(string $format): self

Устанавливает формат сообщения.

**Параметры:**

`$format (string)` - Формат (например: 'markdown', 'html')

**Возвращает:** self - для цепочки вызовов

**Пример:**

```php
$builder->text('**Жирный текст**')->format('markdown');
```

## Добавление вложений
### inlineKeyboard(array $buttons, bool $isOneTime = false): self
Добавляет встроенную клавиатуру.

**Параметры:**

`$buttons (array)` - Массив кнопок (строки с кнопками)

`$isOneTime (bool)` - Одноразовая клавиатура (скрывается после нажатия)

**Возвращает:** self - для цепочки вызовов

**Пример:**

```php
use garmayev\max\MessageBuilder;

$builder->inlineKeyboard([
    MessageBuilder::row([
        MessageBuilder::callbackButton('Кнопка 1', 'action_1'),
        MessageBuilder::callbackButton('Кнопка 2', 'action_2')
    ]),
    MessageBuilder::row([
        MessageBuilder::linkButton('Сайт', 'https://example.com')
    ])
]);
```

### image(string \$tokenOrUrl, ?string \$filename = null, ?int \$width = null, ?int \$height = null): self
Добавляет изображение. Если передан URL, файл будет загружен автоматически.

**Параметры:**

`$tokenOrUrl (string)` - Токен загруженного файла или URL изображения

`$filename (string|null)` - Имя файла (опционально)

`$width (int|null)` - Ширина (опционально)

`$height (int|null)` - Высота (опционально)

**Возвращает:** self - для цепочки вызовов

**Пример:**

```php
// Использование токена
$builder->image($token);

// Использование URL (автоматическая загрузка)
$builder->image('https://example.com/photo.jpg', 'photo.jpg', 800, 600);

// С полными параметрами
$builder->image($token, 'image.jpg', 1920, 1080);
```

### file(string \$tokenOrUrl, ?string \$filename = null): self
Добавляет файл. Если передан URL, файл будет загружен автоматически.

**Параметры:**

`$tokenOrUrl (string)` - Токен загруженного файла или URL файла

`$filename (string|null)` - Имя файла (опционально)

**Возвращает:** self - для цепочки вызовов

**Пример:**

```php
// Использование токена
$builder->file($token, 'document.pdf');

// Использование URL
$builder->file('https://example.com/doc.pdf', 'document.pdf');
```

### video(string $tokenOrUrl): self
Добавляет видео. Если передан URL, файл будет загружен автоматически.

**Параметры:**

`$tokenOrUrl (string)` - Токен загруженного файла или URL видео

**Возвращает:** self - для цепочки вызовов

**Пример:**

```php
$builder->video('https://example.com/video.mp4');
// или
$builder->video($token);
```

### audio(string $tokenOrUrl): self
Добавляет аудио. Если передан URL, файл будет загружен автоматически.

**Параметры:**

`$tokenOrUrl (string)` - Токен загруженного файла или URL аудио

**Возвращает:** self - для цепочки вызовов

**Пример:**

```php
$builder->audio('https://example.com/music.mp3');
// или
$builder->audio($token);
```

### sticker(string $code): self
Добавляет стикер.

**Параметры:**

`$code (string)` - Код стикера

**Возвращает:** self - для цепочки вызовов

**Пример:**

```php
$builder->sticker('sticker_code_123');
```

### location(float \$latitude, float \$longitude): self
Добавляет местоположение.

**Параметры:**

`$latitude (float)` - Широта

`$longitude (float)` - Долгота

**Возвращает:** self - для цепочки вызовов

**Пример:**

```php
$builder->location(55.7558, 37.6173);
```

### contact(string \$name, int \$contactId, ?string \$vcfInfo = null, ?string $vcfPhone = null): self
Добавляет контакт.

**Параметры:**

`$name (string)` - Имя контакта

`$contactId (int)` - ID контакта

`$vcfInfo (string|null)` - VCF информация (опционально)

`$vcfPhone (string|null)` - VCF телефон (опционально)

**Возвращает:** self - для цепочки вызовов

**Пример:**

```php
$builder->contact('Иван Петров', 123, $vcardData, '+79991234567');
```

## Создание кнопок
### static callbackButton(string \$text, string \$payload, ?string \$intent = null): array
Создает кнопку callback.

**Параметры:**

`$text (string)` - Текст кнопки

`$payload (string)` - Данные для callback

`$intent (string|null)` - Намерение (primary, danger, success, warning)

**Возвращает:** array - Массив с данными кнопки

**Пример:**

```php
$button = MessageBuilder::callbackButton('Подтвердить', 'confirm', 'primary');
// или
$button = MessageBuilder::callbackButton('Удалить', 'delete', 'danger');
```

### static linkButton(string \$text, string \$url): array
Создает кнопку ссылки.

**Параметры:**

`$text (string)` - Текст кнопки

`$url (string)` - URL ссылки

**Возвращает:** array - Массив с данными кнопки

**Пример:**

```php
$button = MessageBuilder::linkButton('Перейти на сайт', 'https://example.com');
```

### static messageButton(string $text): array
Создает кнопку сообщения (отправляет текст при нажатии).

**Параметры:**

`$text (string)` - Текст кнопки (будет отправлен при нажатии)

**Возвращает:** array - Массив с данными кнопки

**Пример:**

```php
$button = MessageBuilder::messageButton('Отправить "Привет"');
```
### static requestContactButton(string $text): array
Создает кнопку запроса контакта.

**Параметры:**

`$text (string)` - Текст кнопки

**Возвращает:** array - Массив с данными кнопки

**Пример:**

```php
$button = MessageBuilder::requestContactButton('Поделиться контактом');
```

### static requestLocationButton(string \$text, bool \$quick = false): array
Создает кнопку запроса геолокации.

**Параметры:**

`$text (string)` - Текст кнопки

`$quick (bool)` - Быстрая отправка (без подтверждения)

**Возвращает:** array - Массив с данными кнопки

**Пример:**

```php
$button = MessageBuilder::requestLocationButton('Отправить локацию');
// или с быстрой отправкой
$button = MessageBuilder::requestLocationButton('Где я?', true);
```

### static openAppButton(string \$text, string \$webApp, ?int \$userId = null, ?string \$payload = null): array
Создает кнопку открытия мини-приложения.

**Параметры:**

`$text (string)` - Текст кнопки

`$webApp (string)` - Имя бота или ссылка

`$userId (int|null)` - ID бота (опционально)

`$payload (string|null)` - Данные для передачи (опционально)

**Возвращает:** array - Массив с данными кнопки

**Пример:**

```php
$button = MessageBuilder::openAppButton('Открыть приложение', '@my_bot', 123456, 'start_data');
```

## Вспомогательные методы
### static row(array $buttons): array
Создает строку кнопок для клавиатуры.

**Параметры:**

`$buttons (array)` - Массив кнопок в строке

**Возвращает:** array - Массив с кнопками

**Пример:**

```php
$row = MessageBuilder::row([
    MessageBuilder::callbackButton('Да', 'yes'),
    MessageBuilder::callbackButton('Нет', 'no')
]);
```

### static keyboard(array $rows): array
Создает клавиатуру с несколькими строками.

**Параметры:**

`$rows (array)` - Массив строк кнопок

**Возвращает:** array - Массив строк с кнопками

**Пример:**

```php
$keyboard = MessageBuilder::keyboard([
    MessageBuilder::row([
        MessageBuilder::callbackButton('Кнопка 1', 'action_1')
    ]),
    MessageBuilder::row([
        MessageBuilder::callbackButton('Кнопка 2', 'action_2'),
        MessageBuilder::callbackButton('Кнопка 3', 'action_3')
    ])
]);
```

### build(): array
Формирует итоговое сообщение для отправки.

**Возвращает:** array - Готовый массив сообщения

**Пример:**

```php
$message = MessageBuilder::create('Привет!')
    ->image($token)
    ->build();

// Отправка сообщения
$bot->sendMessage($message, ['user_id' => 123456]);
```

# Типы вложений и кнопок
### Типы вложений

|Метод	|Тип	|Описание|
|--|--|--|
|image()	|image	|Изображение|
|file()	|file	|Файл|
|video()	|video	|Видео|
|audio()	|audio	|Аудио|
|sticker()	|sticker	|Стикер|
|location()	|location	|Геолокация|
|contact()	|contact	|Контакт|
|inlineKeyboard()	|inline_keyboard	|Инлайн-клавиатура|

### Типы кнопок
|Метод	|Тип	|Описание|
|--|--|--|
|callbackButton()	|callback	|Вызов callback-события|
|linkButton()	|link	|Открытие ссылки|
|messageButton()	|message	|Отправка сообщения|
|requestContactButton()	|request_contact	|Запрос контакта|
|requestLocationButton()	|request_geo_location	|Запрос геолокации|
|openAppButton()	|open_app	|Открытие мини-приложения|

## Примеры использования
### Базовый пример
```php
use garmayev\max\MessageBuilder;

// Создание простого сообщения
$message = MessageBuilder::create('Привет, мир!')
    ->text('Добро пожаловать!')
    ->build();

$bot->sendMessage($message, ['user_id' => 123456]);
```

### Сообщение с изображением
```php
$message = MessageBuilder::create('Смотрите это фото!')
    ->image($token, 'photo.jpg', 800, 600)
    ->build();

$bot->sendMessage($message, ['user_id' => 123456]);
```

### Сообщение с клавиатурой
```php
use garmayev\max\MessageBuilder;

$message = MessageBuilder::create('Выберите действие:')
    ->inlineKeyboard([
        MessageBuilder::row([
        MessageBuilder::callbackButton('📊 Статистика', 'stats', 'primary'),
        MessageBuilder::callbackButton('⚙️ Настройки', 'settings', 'default')
    ]),
    MessageBuilder::row([
        MessageBuilder::linkButton('🌐 Сайт', 'https://example.com')
    ]),
    MessageBuilder::row([
        MessageBuilder::callbackButton('❌ Закрыть', 'close', 'danger')
    ])
])
->build();

$bot->sendMessage($message, ['user_id' => 123456]);
```

### Сообщение с несколькими вложениями
```php
$message = MessageBuilder::create('Новый пост!')
    ->image($imageToken, 'photo.jpg')
    ->file($fileToken, 'document.pdf')
    ->location(55.7558, 37.6173)
    ->build();

$bot->sendMessage($message, ['chat_id' => 789012, 'chat_type' => 'chat']);
```

### Сообщение с контактом
```php
$vcard = "BEGIN:VCARD\nVERSION:3.0\nFN:Иван Петров\nTEL:+79991234567\nEND:VCARD";

$message = MessageBuilder::create('Контакт пользователя:')
->contact('Иван Петров', 123, $vcard, '+79991234567')
->build();

$bot->sendMessage($message, ['user_id' => 123456]);
```

### Сложное сообщение
```php
$message = MessageBuilder::create()
    ->text('**Важное объявление**')
    ->format('markdown')
    ->image($imageToken, 'announcement.jpg', 1200, 800)
    ->inlineKeyboard([
        MessageBuilder::row([
            MessageBuilder::callbackButton('👍 Подробнее', 'more', 'primary')
        ]),
        MessageBuilder::row([
            MessageBuilder::linkButton('🔗 Читать статью', 'https://example.com/article')
        ])
    ])
    ->build();

$bot->sendMessage($message, ['chat_id' => $chatId, 'chat_type' => 'chat']);
```

# Практические сценарии
### Сценарий 1: Бот с меню
```php
public function showMenu($userId)
{
    $message = MessageBuilder::create('🏠 Главное меню:')
        ->inlineKeyboard([
            MessageBuilder::row([
                MessageBuilder::callbackButton('📝 Мои данные', 'profile', 'primary'),
                MessageBuilder::callbackButton('📊 Статистика', 'stats', 'default')
            ]),
            MessageBuilder::row([
                MessageBuilder::callbackButton('📦 Заказы', 'orders', 'default'),
                MessageBuilder::callbackButton('💳 Оплата', 'payment', 'success')
            ]),
            MessageBuilder::row([
                MessageBuilder::callbackButton('❓ Помощь', 'help', 'default'),
                MessageBuilder::callbackButton('🚪 Выход', 'logout', 'danger')
            ])
        ])
        ->build();

    $this->bot->sendMessage($message, ['user_id' => $userId]);
}
```

### Сценарий 2: Отправка карточки товара
```php
public function sendProductCard($userId, $product)
{
$message = MessageBuilder::create()
    ->text("**{$product['name']}**\n\n{$product['description']}\n\n💰 Цена: {$product['price']} руб.")
    ->format('markdown')
    ->image($product['image_token'], $product['image_name'], 800, 600)
    ->inlineKeyboard([
        MessageBuilder::row([
            MessageBuilder::callbackButton('🛒 В корзину', "add_cart_{$product['id']}", 'primary')
        ]),
        MessageBuilder::row([
            MessageBuilder::linkButton('🔍 Подробнее', $product['url'])
        ])
    ])
    ->build();

    $this->bot->sendMessage($message, ['user_id' => $userId]);
}
```

### Сценарий 3: Отправка локации с картой
```php
public function sendLocation($userId, $name, $lat, $lng, $address)
{
$message = MessageBuilder::create()
    ->text("📍 **{$name}**\n\nАдрес: {$address}")
    ->format('markdown')
    ->location($lat, $lng)
    ->inlineKeyboard([
        MessageBuilder::row([
            MessageBuilder::linkButton('🗺️ Открыть в картах', "https://maps.google.com/?q={$lat},{$lng}")
        ])
    ])
    ->build();

    $this->bot->sendMessage($message, ['user_id' => $userId]);
}
```

### Сценарий 4: Отправка файла с описанием
```php
public function sendFile($userId, $fileUrl, $filename, $description)
{
    $message = MessageBuilder::create()
        ->text("📄 **Документ:** {$filename}\n\n{$description}")
        ->format('markdown')
        ->file($fileUrl, $filename)
        ->inlineKeyboard([
            MessageBuilder::row([
                MessageBuilder::callbackButton('📥 Скачать', 'download', 'primary')
            ])
        ])
        ->build();

    $this->bot->sendMessage($message, ['user_id' => $userId]);
}
```
### Сценарий 5: Анкета с вопросами
```php
public function sendSurvey($userId)
{
    $message = MessageBuilder::create('📋 **Опрос**\n\nОтветьте на несколько вопросов:')
        ->format('markdown')
        ->inlineKeyboard([
            MessageBuilder::row([
                MessageBuilder::callbackButton('✅ Да', 'survey_yes', 'success'),
                MessageBuilder::callbackButton('❌ Нет', 'survey_no', 'danger')
            ]),
            MessageBuilder::row([
                MessageBuilder::callbackButton('🔄 Возможно', 'survey_maybe', 'default')
            ])
        ])
        ->build();

    $this->bot->sendMessage($message, ['user_id' => $userId]);
}
```

### Сценарий 6: Отправка мультимедийного сообщения
```php
public function sendMediaMessage($chatId, $imageUrl, $videoUrl, $audioUrl, $text)
{
    $message = MessageBuilder::create($text)
        ->image($imageUrl, 'cover.jpg', 1200, 800)
        ->video($videoUrl)
        ->audio($audioUrl)
        ->inlineKeyboard([
            MessageBuilder::row([
                MessageBuilder::linkButton('▶️ Смотреть видео', $videoUrl),
                MessageBuilder::linkButton('🎵 Слушать аудио', $audioUrl)
            ])
        ])
        ->build();

    $this->bot->sendMessage($message, ['chat_id' => $chatId, 'chat_type' => 'chat']);
}
```

### Сценарий 7: Сообщение с несколькими файлами
```php
public function sendMultipleFiles($userId, array $files)
{
    $builder = MessageBuilder::create('📎 **Прикрепленные файлы:**')
        ->format('markdown');

    foreach ($files as $file) {
        $builder->file($file['token'], $file['name']);
    }
    
    $message = $builder->build();
    $this->bot->sendMessage($message, ['user_id' => $userId]);
}
```

## Особенности и рекомендации
1. Автоматическая загрузка файлов
   При передаче URL в методы image(), file(), video(), audio() файл будет автоматически скачан и загружен на сервер MAX:

```php
// Автоматическая загрузка
$builder->image('https://example.com/photo.jpg');
// Эквивалентно ручной загрузке
$token = $bot->upload('/path/to/photo.jpg', 'image')['token'];
$builder->image($token);
```

2. Управление размером изображений
   Для изображений можно указать размеры:

```php
$builder->image($token, 'photo.jpg', 1920, 1080);
```

3. Одноразовые клавиатуры
```php
   $builder->inlineKeyboard($buttons, true); // Скрывается после нажатия
```
4. Intent для кнопок
   Intents влияют на визуальное оформление кнопок:

```php
// Доступные intents
'primary'  // Основная (синяя)
'success'  // Успех (зеленая)
'danger'   // Опасность (красная)
'warning'  // Предупреждение (желтая)
'default'  // Обычная (серая)
```

5. Цепочка вызовов
   Все методы возвращают $this, что позволяет создавать цепочки:

```php
$message = MessageBuilder::create()
    ->text('Сообщение')
    ->image($token)
    ->inlineKeyboard($buttons)
    ->build();
```
6. Обработка ошибок
   При загрузке файлов по URL могут возникать ошибки:

```php
try {
    $message = MessageBuilder::create()
        ->image('https://example.com/photo.jpg')
        ->build();
} catch (\Exception $e) {
    Yii::error('Ошибка загрузки файла: ' . $e->getMessage());
    // Использование fallback сообщения
    $message = MessageBuilder::create('Не удалось загрузить изображение')
        ->build();
}
```

7. Комбинирование с другими методами
   MessageBuilder отлично комбинируется с другими компонентами:

```php
// С использованием EventHandler
$handler->onMessage(function(Request $request) {
    $user = $request->getUser();
    $message = MessageBuilder::create('Привет!')
        ->inlineKeyboard([
            MessageBuilder::row([
                MessageBuilder::callbackButton('Показать ID', 'show_id')
            ])
        ])
        ->build();

    $this->bot->sendMessage($message, ['user_id' => $user->getUser_id()]);
});

// С использованием компонента Yii
$component = Yii::$app->max;
$message = MessageBuilder::create('Тест')->build();
$component->sendMessage($message, ['user_id' => 123]);
```

# Заключение
MessageBuilder предоставляет удобный и интуитивно понятный способ создания сообщений для платформы MAX. Он значительно упрощает процесс построения сложных сообщений с различными вложениями и клавиатурами, обеспечивая чистый и читаемый код. Используйте его вместе с классом Max для создания эффективных чат-ботов.