# Описание

Класс Max является основным классом для взаимодействия с API MAX. Он предоставляет методы для отправки сообщений, управления вебхуками, загрузки файлов и получения информации.

# Конфигурация
```php
// В конфигурации Yii2
'components' => [
    'max' => [
        'class' => 'garmayev\max\Max',
        'access_token' => 'your_access_token',
        'secret' => 'your_secret_key'
    ],
],
```

# Свойства

|Свойство	|Тип	|Описание |
|--|--|--|
|access_token	|string	|Токен доступа для авторизации в API |
|secret	|string	|Секретный ключ для вебхуков|

# Методы
## Работа с сообщениями

`sendMessage(array $params, array $args = []): Response`
Отправляет сообщение.

### Параметры:

$params - параметры сообщения (text, attachments и т.д.)

$args - query параметры (user_id, chat_id, chat_type)

### Пример:

```php
$response = $max->sendMessage(
    ['text' => 'Привет, мир!'],
    ['user_id' => 123456]
);
```

`editMessage(array $params, array $args = []): Response`

Редактирует существующее сообщение.

### Параметры:

$params - новые параметры сообщения

$args - query параметры (обязательно message_id)

### Пример:

```php
$response = $max->editMessage(
    ['text' => 'Новый текст'],
    ['message_id' => 789012]
);
```
`deleteMessage(array $args = []): Response`

Удаляет сообщение.

### Параметры:

$args - query параметры (обязательно message_id)

### Пример:

```php
$response = $max->deleteMessage(['message_id' => 789012]);
```

`sendAnswer(array $params, array $args = []): Response`

Отправляет ответ на callback.

### Параметры:

$params - параметры сообщения

$args - query параметры (обязательно callback_id)

`sendCallbackAnswer(array $args = []): Response`

Отправляет уведомление о выполнении действия.

### Параметры:

$args - query параметры (обязательно callback_id)

### Пример:

```php
$response = $max->sendCallbackAnswer(['callback_id' => 123]);
```

## Работа с вебхуками
`setWebhook(string $url, array $types): Response`
Устанавливает вебхук для получения обновлений.

### Параметры:

$url - URL вебхука

$types - типы событий для подписки

### Пример:

```php
$response = $max->setWebhook('https://example.com/webhook', ['bot_started', 'message_created']);
```

`deleteWebhook(string $url): Response`

Удаляет вебхук.

### Параметры:

$url - URL вебхука для удаления

## Получение информации

`getUserInfo(array $args = []): Response`

Получает информацию о пользователе.

### Параметры:

$args - query параметры (обязательно user_id)

### Пример:

```php
$response = $max->getUserInfo(['user_id' => 123456]);
```

`getChatInfo(array $args = []): Response`

Получает информацию о чате.

### Параметры:

$args - query параметры (обязательно user_id)

### Пример:

```php
$response = $max->getChatInfo(['chat_id' => 789012]);
```

## Работа с файлами
`getUploadUrl(string $type = 'file'): Response`

Получает URL для загрузки файла.

### Параметры:

$type - тип файла (image, video, audio, file)

### Пример:

```php
$response = $max->getUploadUrl('image');
$uploadUrl = $response->getData()['url'];
```

`uploadFile(string $uploadUrl, string $filePath): array`

Загружает файл по полученному URL.

### Параметры:

$uploadUrl - URL из метода getUploadUrl

$filePath - путь к файлу

### Возвращает: массив с токеном файла

`upload(string $filePath, string $type = 'file', bool $waitProcessing = false): array`

Полный цикл загрузки файла.

### Параметры:

$filePath - путь к файлу

$type - тип файла (image, video, audio, file)

$waitProcessing - ждать ли обработки файла

### Возвращает: массив с токеном и информацией о файле

### Пример:

```php
$result = $max->upload('/path/to/image.jpg', 'image');
$token = $result['token'];
```

`createAttachment(string $token, string $type = 'file'): array`

Создает структуру вложения для сообщения.

### Параметры:

$token - токен загруженного файла

$type - тип вложенияПример:

```php
$attachment = $max->createAttachment($token, 'image');
```

`sendMessageWithAttachment($recipientId, string $text, string $fileToken, string $fileType = 'image', string $chatType = 'user'): Response`

Упрощенный метод отправки сообщения с вложением.

### Параметры:

$recipientId - ID получателя

$text - текст сообщения

$fileToken - токен файла

$fileType - тип файла

$chatType - тип чата

### Пример:

```php
$response = $max->sendMessageWithAttachment(123456, 'Смотрите фото!', $token, 'image');
```

## Обработка событий
`handler(): EventHandler`

Инициализирует обработчик событий.

### Пример:

```php
$max->handler()
    ->onMessage(function($update) {
        // Обработка сообщения
    })
    ->run();
```