# Описание
Класс MessageBuilder предоставляет удобный интерфейс для построения сообщений с поддержкой автоматической загрузки файлов из URL.

# Основные методы
## `static create(?string $text = null, array $args = []): self`

Создает новый экземпляр билдера.

### Параметры:

$text - текст сообщения (опционально)

$args - дополнительные аргументы (chat_id, chat_type, user_id)

### Пример:

```php
$builder = MessageBuilder::create('Привет!', ['user_id' => 123456]);
```

## `text(string $text): self`

Устанавливает текст сообщения.

### Пример:

```php
$builder->text('Новый текст');
```


## `format(string $format): self`
Устанавливает формат сообщения (markdown, html).

### Пример:

```php
$builder->format('markdown');
```

## `inlineKeyboard(array $buttons, bool $isOneTime = false): self`

Добавляет встроенную клавиатуру.

### Параметры:

$buttons - массив кнопок

$isOneTime - одноразовая ли клавиатура

### Пример:

```php
$builder->inlineKeyboard([
    MessageBuilder::row([
        MessageBuilder::callbackButton('Кнопка 1', 'data1'),
        MessageBuilder::callbackButton('Кнопка 2', 'data2')
    ])
]);
```
# Методы для создания кнопок (статические)
## `static callbackButton(string $text, string $payload, ?string $intent = null): array`
Создает callback кнопку.

### Пример:

```php
$button = MessageBuilder::callbackButton('Нажми меня', 'action_data', 'primary');
```

## `static linkButton(string $text, string $url): array`
Создает кнопку-ссылку.

### Пример:

```php
$button = MessageBuilder::linkButton('Перейти на сайт', 'https://example.com');
```

## `static messageButton(string $text): array`
Создает кнопку, которая отправляет сообщение.

### Пример:

```php
$button = MessageBuilder::messageButton('Отправить текст');
```

## `static requestContactButton(string $text): array`
Создает кнопку запроса контакта.

### Пример:

```php
$button = MessageBuilder::requestContactButton('Поделиться контактом');
```

## `static requestLocationButton(string $text, bool $quick = false): array`
Создает кнопку запроса геолокации.

### Параметры:

$quick - быстрая отправка без подтверждения

### Пример:

```php
$button = MessageBuilder::requestLocationButton('Поделиться местоположением', true);
```

## `static openAppButton(string $text, string $webApp, ?int $userId = null, ?string $payload = null): array`
Создает кнопку открытия мини-приложения.

### Пример:

```php
$button = MessageBuilder::openAppButton('Открыть приложение', 'my_bot', 123456, 'custom_data');
```

# Методы для добавления медиа
## `image(string $tokenOrUrl, ?string $filename = null, ?int $width = null, ?int $height = null): self`
Добавляет изображение. Автоматически определяет URL или токен. Если передан URL - файл будет автоматически загружен.

### Пример:

```php
// С URL (автоматическая загрузка)
$builder->image('https://example.com/photo.jpg', 'photo.jpg', 800, 600);

// С токеном (уже загружен)
$builder->image('existing_token_123', 'photo.jpg');
```

## `file(string $tokenOrUrl, ?string $filename = null): self`
Добавляет файл. Автоматически определяет URL или токен.

### Пример:

```php
$builder->file('https://example.com/document.pdf', 'doc.pdf');
```
## `video(string $tokenOrUrl): self`
Добавляет видео. Автоматически определяет URL или токен.

### Пример:

```php
$builder->video('https://example.com/video.mp4');
```

## `audio(string $tokenOrUrl): self`
Добавляет аудио. Автоматически определяет URL или токен.

### Пример:

```php
$builder->audio('https://example.com/audio.mp3');
```

## `sticker(string $code): self`
Добавляет стикер.

### Пример:

```php
$builder->sticker('sticker_code_123');
```

## `location(float $latitude, float $longitude): self`
Добавляет местоположение.

### Пример:

```php
$builder->location(55.751244, 37.618423);
```

## `contact(string $name, int $contactId, ?string $vcfInfo = null, ?string $vcfPhone = null): self`
Добавляет контакт.

### Пример:

```php
$builder->contact('Иван Иванов', 123456, 'vcf_data', '+71234567890');
```

# Вспомогательные методы
## `static row(array $buttons): array`
Создает строку кнопок для клавиатуры.

### Пример:

```php
$row = MessageBuilder::row([$button1, $button2]);
```

## `static keyboard(array $rows): array`
Создает клавиатуру из нескольких строк.

### Пример:

```php
$keyboard = MessageBuilder::keyboard([
MessageBuilder::row([$button1, $button2]),
MessageBuilder::row([$button3])
]);
```

## `build(): array`
Формирует итоговое сообщение для отправки.

### Пример:

```php
$message = MessageBuilder::create('Привет!')
    ->image('https://example.com/photo.jpg')
    ->build();
$response = \Yii::$app->max->sendMessage($message, ['user_id' => 123456]);
```

# Полные примеры использования
## Пример 1: Простое текстовое сообщение
```php
$message = MessageBuilder::create('Привет, как дела?')->build();
$response = \Yii::$app->max->sendMessage($message, ['user_id' => 123456]);
```

## Пример 2: Сообщение с изображением из URL
```php
$message = MessageBuilder::create('Смотрите какое красивое фото!')
    ->image('https://example.com/sunset.jpg', 'sunset.jpg', 1024, 768)
    ->build();

$response = \Yii::$app->max->sendMessage($message, ['user_id' => 123456]);
```

## Пример 3: Сообщение с клавиатурой
```php
$keyboard = MessageBuilder::keyboard([
    MessageBuilder::row([
        MessageBuilder::callbackButton('Да', 'yes', 'primary'),
        MessageBuilder::callbackButton('Нет', 'no', 'danger')
    ]),
    MessageBuilder::row([
        MessageBuilder::linkButton('Подробнее', 'https://example.com')
    ])
]);

$message = MessageBuilder::create('Выберите вариант ответа:')
    ->inlineKeyboard($keyboard)
    ->build();

$response = \Yii::$app->max->sendMessage($message, ['user_id' => 123456]);
```
## Пример 4: Сообщение с несколькими файлами
```php
$message = MessageBuilder::create('Мультимедиа сообщение')
    ->image('https://example.com/photo1.jpg')
    ->image('https://example.com/photo2.jpg')
    ->video('https://example.com/video.mp4')
    ->file('https://example.com/document.pdf', 'important.pdf')
    ->audio('existing_audio_token')
    ->build();

$response = \Yii::$app->max->sendMessage($message, ['chat_id' => 789012, 'chat_type' => 'chat']);
```

## Пример 5: Сообщение с запросом контакта и геолокации
```php
$keyboard = MessageBuilder::keyboard([
    MessageBuilder::row([
        MessageBuilder::requestContactButton('Поделиться контактом'),
        MessageBuilder::requestLocationButton('Поделиться местоположением', true)
    ])
]);

$message = MessageBuilder::create('Для оформления заказа поделитесь контактом или местоположением:')
    ->inlineKeyboard($keyboard)
    ->build();

$response = \Yii::$app->max->sendMessage($message, ['user_id' => 123456]);
```
Пример 6: Отправка в чат (не в личку)
```php
$message = MessageBuilder::create('Всем привет!')
    ->image('https://example.com/announcement.jpg')
    ->build();

$response = \Yii::$app->max->sendMessage($message, [
    'chat_id' => 789012,
    'chat_type' => 'chat'
]);
```

# Важные замечания
- Автоматическая загрузка файлов: При передаче URL в методы image(), file(), video(), audio() файл автоматически скачивается и загружается на сервер MAX. Временные файлы автоматически удаляются.
- Токены: Если у вас уже есть токен файла (например, от предыдущих загрузок), вы можете передать его напрямую.
- Компонент MAX: Для работы автоматической загрузки файлов должен быть настроен компонент max в Yii2.
- Обработка ошибок: При загрузке файлов могут возникать исключения. Рекомендуется оборачивать вызовы в try-catch блоки.

