<?php

namespace garmayev\max;

use garmayev\max\types\Update;
use garmayev\max\base\MaxBase;

/**
 * @property string $access_token
 * @property string $secret
 */
class Max extends MaxBase
{
    public string $access_token;
    public string $secret;

    private EventHandler $handler;

    /**
     * @param array $config Конфигурация бота
     */
    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->handler = new EventHandler($this);
    }

    /**
     * Инициализирует обработчик событий
     *
     * @return EventHandler
     */
    public function handler(): EventHandler
    {
        return $this->handler->init();
    }

    /**
     * Установка вебхука
     *
     * @param string $url URL вебхука
     * @param array $types Типы событий для подписки
     * @return \garmayev\max\types\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function setWebhook(string $url, array $types)
    {
        return parent::send('POST', 'subscriptions', [
            'url' => $url,
            'update_types' => $types,
            'secret' => $this->secret,
        ]);
    }

    public function getWebhooks()
    {
        return parent::send('GET', 'subscriptions');
    }

    /**
     * Удаление вебхука
     *
     * @return \garmayev\max\types\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteWebhook($url)
    {
        return parent::send('DELETE', 'subscriptions', [], [
            'url' => $url
        ]);
    }

    /**
     * Отправка сообщения
     *
     * @param array $params Параметры сообщения
     * @param array $args Query параметры (user_id, chat_id, chat_type)
     * @return \garmayev\max\types\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendMessage(array $params, array $args = [])
    {
        return parent::send('POST', 'messages', $params, $args);
    }

    /**
     * Редактирование сообщения
     *
     * @param array $params Новые параметры сообщения
     * @param array $args Query параметры (message_id - обязательно)
     * @return \garmayev\max\types\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function editMessage(array $params, array $args = [])
    {
        return parent::send('PUT', 'messages', $params, $args);
    }

    /**
     * Удаление сообщения
     *
     * @param array $args Query параметры (message_id - обязательно)
     * @return \garmayev\max\types\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteMessage(array $args = [])
    {
        return parent::send('DELETE', 'messages', [], $args);
    }

    /**
     * Отправка ответа на callback
     *
     * @param array $params Параметры сообщения
     * @param array $args Query параметры (callback_id - обязательно)
     * @return \garmayev\max\types\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendAnswer(array $params, array $args = [])
    {
        return parent::send('POST', 'answers', $params, $args);
    }

    /**
     * Получение информации о пользователе
     *
     * @param array $args Query параметры (user_id - обязательно)
     * @return \garmayev\max\types\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUserInfo(array $args = [])
    {
        return parent::send('GET', 'users', [], $args);
    }

    /**
     * Получение информации о чате
     *
     * @param array $args Query параметры (chat_id - обязательно)
     * @return \garmayev\max\types\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getChatInfo(array $args = [])
    {
        return parent::send('GET', 'chats', [], $args);
    }

    /**
     * Отправка уведомления о выполнении действия
     *
     * @param array $args Query параметры (callback_id - обязательно)
     * @return \garmayev\max\types\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendCallbackAnswer(array $args = [])
    {
        return parent::send('POST', 'answers', [
            'message' => [
                'text' => '✅ Действие выполнено'
            ]
        ], $args);
    }

    /**
     * Получение URL для загрузки файла
     *
     * @param string $type Тип загружаемого файла (image, video, audio, file)
     * @return \garmayev\max\types\Response Ответ с полем 'url'
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUploadUrl(string $type = 'file')
    {
        $allowedTypes = ['image', 'video', 'audio', 'file'];
        if (!in_array($type, $allowedTypes)) {
            throw new \InvalidArgumentException("Invalid upload type. Allowed: " . implode(', ', $allowedTypes));
        }

        return parent::send('POST', 'uploads', [], ['type' => $type]);
    }

    /**
     * Загрузка файла по полученному URL (multipart upload)
     *
     * @param string $uploadUrl URL из метода getUploadUrl
     * @param string $filePath Путь к файлу для загрузки
     * @param string|null $filename Исходное имя файла для multipart
     * @return array JSON-ответ или пустой массив при успешном XML-подтверждении
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadFile(string $uploadUrl, string $filePath, ?string $filename = null)
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            throw new \InvalidArgumentException("File not found: {$filePath}");
        }

        $mimeType = mime_content_type($filePath);
        $fileName = $filename ?? basename($filePath);
        $stream = fopen($filePath, 'rb');
        if ($stream === false) {
            throw new \RuntimeException("Failed to open file: {$filePath}");
        }

        $multipart = [
            [
                'name' => 'data',
                'contents' => $stream,
                'filename' => $fileName,
                'headers' => ['Content-Type' => $mimeType ?: 'application/octet-stream']
            ]
        ];

        $options = [
            'headers' => [
                'Authorization' => $this->access_token,
            ],
            'multipart' => $multipart,
        ];

        try {
            $response = $this->client->request('POST', $uploadUrl, $options);
            $responseBody = $response->getBody()->getContents();

            $data = json_decode($responseBody, true);
            if (is_array($data)) {
                if (!empty($data['error']) || (isset($data['success']) && $data['success'] === false)) {
                    throw new \RuntimeException('Media upload failed');
                }
                return $data;
            }
            // Аудио/видео подтверждают успешную загрузку XML, токен уже выдан /uploads.
            if (preg_match('~^(?:<\?xml[^>]*\?>\s*)?<retval>\s*1\s*</retval>$~', trim($responseBody))) {
                return [];
            }
            throw new \RuntimeException('Unexpected media upload response');
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            \Yii::error([
                'error' => $e->getMessage(),
                'url' => $uploadUrl,
                'file' => $filePath
            ]);
            throw $e;
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }
    }

    /**
     * Полный цикл загрузки файла: получение URL и загрузка
     *
     * @param string $filePath Путь к файлу
     * @param string $type Тип файла (image, video, audio, file)
     * @param bool $waitProcessing Сделать паузу 3 секунды для аудио/видео (не гарантирует готовность)
     * @param string|null $filename Исходное имя файла для multipart
     * @return array Результат с токеном и информацией о файле
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function upload(string $filePath, string $type = 'file', bool $waitProcessing = false, ?string $filename = null)
    {
        // Проверяем существование файла
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("File not found: {$filePath}");
        }

        // 1. Получаем URL для загрузки
        $uploadUrlResponse = $this->getUploadUrl($type);

        // Используем новый метод getUrl() из класса Response
        $uploadUrl = $uploadUrlResponse->getUrl();

        if (!$uploadUrl) {
            throw new \RuntimeException("Failed to get upload URL. Response: " . json_encode($uploadUrlResponse->getData()));
        }

        // 2. Загружаем файл по полученному URL
        $uploadResult = $this->uploadFile($uploadUrl, $filePath, $filename);

        // 3. Получаем токен из ответа загрузки
        $token = $uploadResult['token'] ?? null;
        if ($type === 'image' && !$token) {
            foreach ($uploadResult['photos'] ?? [] as $photo) {
                if (is_array($photo) && !empty($photo['token'])) {
                    $token = $photo['token'];
                    break;
                }
            }
        }
        if (($type === 'video' || $type === 'audio') && !$token) {
            $token = $uploadUrlResponse->getToken();
        }

        if (!is_string($token) || $token === '') {
            throw new \RuntimeException("Failed to get token after file upload. Response: " . json_encode($uploadResult));
        }

        // 4. Формируем результат
        $result = [
            'token' => $token,
            'type' => $type,
            'file_path' => $filePath,
        ];

        // 5. Для видео и аудио может потребоваться дополнительная обработка
        if ($waitProcessing && ($type === 'video' || $type === 'audio')) {
            // Рекомендуемая пауза после загрузки больших файлов
            // для их обработки на сервере MAX
            sleep(3);
        }

        return $result;
    }

    /**
     * Создание вложения для сообщения
     *
     * @param string $token Токен загруженного файла
     * @param string $type Тип вложения (image, video, audio, file)
     * @return array
     */
    public function createAttachment(string $token, string $type = 'file'): array
    {
        $allowedTypes = ['image', 'video', 'audio', 'file'];
        if (!in_array($type, $allowedTypes)) {
            throw new \InvalidArgumentException("Invalid attachment type. Allowed: " . implode(', ', $allowedTypes));
        }

        return [
            'type' => $type,
            'payload' => [
                'token' => $token
            ]
        ];
    }

    /**
     * Отправка сообщения с вложением (упрощенный метод)
     *
     * @param string|int $recipientId ID получателя (user_id или chat_id)
     * @param string $text Текст сообщения
     * @param string $fileToken Токен загруженного файла
     * @param string $fileType Тип файла (image, video, audio, file)
     * @param string $chatType Тип чата (user, chat, channel)
     * @return \garmayev\max\types\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendMessageWithAttachment($recipientId, string $text, string $fileToken, string $fileType = 'image', string $chatType = 'user')
    {
        $attachment = $this->createAttachment($fileToken, $fileType);

        $params = [
            'text' => $text,
            'attachments' => [$attachment]
        ];

        $args = [
            'user_id' => $recipientId,
            'chat_type' => $chatType
        ];

        // Для чатов и каналов используем chat_id
        if ($chatType !== 'user') {
            $args['chat_id'] = $recipientId;
            unset($args['user_id']);
        }

        return $this->sendMessage($params, $args);
    }
}
