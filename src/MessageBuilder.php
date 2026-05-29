<?php

namespace garmayev\max;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use garmayev\max\types\buttons\Callback;
use garmayev\max\types\buttons\Link;
use garmayev\max\types\buttons\Message as MessageButton;
use garmayev\max\types\buttons\RequestContact;
use garmayev\max\types\buttons\RequestGeoLocation;
use garmayev\max\types\buttons\OpenApp;

/**
 * Конструктор сообщений для MAX API
 */
class MessageBuilder
{
    private array $message = [];
    private array $attachments = [];
    private Client $client;

    /**
     * Создает новый экземпляр конструктора сообщений
     *
     * @param string|null $text Текст сообщения
     * @param array $args Дополнительные аргументы (chat_id, chat_type, user_id)
     * @return MessageBuilder
     */
    public static function create(?string $text = null, array $args = []): self
    {
        $builder = new self();
        $builder->message = $args;

        if ($text !== null) {
            $builder->text($text);
        }

        return $builder;
    }

    /**
     * Устанавливает текст сообщения
     *
     * @param string $text Текст сообщения
     * @return $this
     */
    public function text(string $text): self
    {
        $this->message['text'] = $text;
        return $this;
    }

    /**
     * Устанавливает формат сообщения
     *
     * @param string $format Формат (markdown, html и т.д.)
     * @return $this
     */
    public function format(string $format): self
    {
        $this->message['format'] = $format;
        return $this;
    }

    /**
     * Добавляет встроенную клавиатуру
     *
     * @param array $buttons Массив кнопок
     * @param bool $isOneTime Одноразовая клавиатура
     * @return $this
     */
    public function inlineKeyboard(array $buttons, bool $isOneTime = false): self
    {
        $this->attachments[] = [
            'type' => 'inline_keyboard',
            'payload' => [
                'buttons' => $buttons,
                'one_time' => $isOneTime
            ]
        ];
        return $this;
    }

    /**
     * Создает кнопку callback
     *
     * @param string $text Текст кнопки
     * @param string $payload Данные для callback
     * @param string|null $intent Намерение (primary, danger и т.д.)
     * @return array
     */
    public static function callbackButton(string $text, string $payload, ?string $intent = null): array
    {
        $button = [
            'type' => Callback::TYPE_CALLBACK,
            'text' => $text,
            'payload' => $payload
        ];

        if ($intent !== null) {
            $button['intent'] = $intent;
        }

        return $button;
    }

    /**
     * Создает кнопку ссылки
     *
     * @param string $text Текст кнопки
     * @param string $url URL ссылки
     * @return array
     */
    public static function linkButton(string $text, string $url): array
    {
        return [
            'type' => Link::TYPE_LINK,
            'text' => $text,
            'url' => $url
        ];
    }

    /**
     * Создает кнопку сообщения
     *
     * @param string $text Текст кнопки (будет отправлен при нажатии)
     * @return array
     */
    public static function messageButton(string $text): array
    {
        return [
            'type' => MessageButton::TYPE_MESSAGE,
            'text' => $text
        ];
    }

    /**
     * Создает кнопку запроса контакта
     *
     * @param string $text Текст кнопки
     * @return array
     */
    public static function requestContactButton(string $text): array
    {
        return [
            'type' => RequestContact::TYPE_REQUEST_CONTACT,
            'text' => $text
        ];
    }

    /**
     * Создает кнопку запроса геолокации
     *
     * @param string $text Текст кнопки
     * @param bool $quick Быстрая отправка (без подтверждения)
     * @return array
     */
    public static function requestLocationButton(string $text, bool $quick = false): array
    {
        return [
            'type' => RequestGeoLocation::TYPE_REQUEST_GEO_LOCATION,
            'text' => $text,
            'quick' => $quick
        ];
    }

    /**
     * Создает кнопку открытия мини-приложения
     *
     * @param string $text Текст кнопки
     * @param string $webApp Имя бота или ссылка
     * @param int|null $userId ID бота
     * @param string|null $payload Данные для передачи
     * @return array
     */
    public static function openAppButton(string $text, string $webApp, ?int $userId = null, ?string $payload = null): array
    {
        $button = [
            'type' => OpenApp::TYPE_OPEN_APP,
            'text' => $text,
            'web_app' => $webApp
        ];

        if ($userId !== null) {
            $button['user_id'] = $userId;
        }

        if ($payload !== null) {
            $button['payload'] = $payload;
        }

        return $button;
    }

    /**
     * Добавляет изображение
     * Если передан URL - файл будет загружен автоматически
     *
     * @param string $tokenOrUrl Токен (уже загружен) или URL файла
     * @param string|null $filename Имя файла
     * @param int|null $width Ширина
     * @param int|null $height Высота
     * @return $this
     * @throws \Exception
     */
    public function image(string $tokenOrUrl, ?string $filename = null, ?int $width = null, ?int $height = null): self
    {
        if ($this->isUrl($tokenOrUrl)) {
            $token = $this->uploadFileFromUrl($tokenOrUrl, 'image');
            $attachment = [
                'type' => 'image',
                'payload' => [
                    'token' => $token
                ]
            ];

            if ($filename !== null) {
                $attachment['filename'] = $filename;
            }

            if ($width !== null) {
                $attachment['width'] = $width;
            }

            if ($height !== null) {
                $attachment['height'] = $height;
            }

            $this->attachments[] = $attachment;
        } else {
            $attachment = [
                'type' => 'image',
                'payload' => [
                    'token' => $tokenOrUrl
                ]
            ];

            if ($filename !== null) {
                $attachment['filename'] = $filename;
            }

            if ($width !== null) {
                $attachment['width'] = $width;
            }

            if ($height !== null) {
                $attachment['height'] = $height;
            }

            $this->attachments[] = $attachment;
        }

        return $this;
    }

    /**
     * Добавляет стикер
     *
     * @param string $code Код стикера
     * @return $this
     */
    public function sticker(string $code): self
    {
        $this->attachments[] = [
            'type' => 'sticker',
            'payload' => [
                'code' => $code
            ]
        ];
        return $this;
    }

    /**
     * Добавляет файл
     * Если передан URL - файл будет загружен автоматически
     *
     * @param string $tokenOrUrl Токен (уже загружен) или URL файла
     * @param string|null $filename Имя файла
     * @return $this
     * @throws \Exception
     */
    public function file(string $tokenOrUrl, ?string $filename = null): self
    {
        if ($this->isUrl($tokenOrUrl)) {
            $token = $this->uploadFileFromUrl($tokenOrUrl, 'file');
            $attachment = [
                'type' => 'file',
                'payload' => [
                    'token' => $token
                ]
            ];

            if ($filename !== null) {
                $attachment['filename'] = $filename;
            }

            $this->attachments[] = $attachment;
        } else {
            $attachment = [
                'type' => 'file',
                'payload' => [
                    'token' => $tokenOrUrl
                ]
            ];

            if ($filename !== null) {
                $attachment['filename'] = $filename;
            }

            $this->attachments[] = $attachment;
        }

        return $this;
    }

    /**
     * Добавляет видео
     * Если передан URL - файл будет загружен автоматически
     *
     * @param string $tokenOrUrl Токен (уже загружен) или URL файла
     * @return $this
     * @throws \Exception
     */
    public function video(string $tokenOrUrl): self
    {
        if ($this->isUrl($tokenOrUrl)) {
            $token = $this->uploadFileFromUrl($tokenOrUrl, 'video');
            $this->attachments[] = [
                'type' => 'video',
                'payload' => [
                    'token' => $token
                ]
            ];
        } else {
            $this->attachments[] = [
                'type' => 'video',
                'payload' => [
                    'token' => $tokenOrUrl
                ]
            ];
        }

        return $this;
    }

    /**
     * Добавляет аудио
     * Если передан URL - файл будет загружен автоматически
     *
     * @param string $tokenOrUrl Токен (уже загружен) или URL файла
     * @return $this
     * @throws \Exception
     */
    public function audio(string $tokenOrUrl): self
    {
        if ($this->isUrl($tokenOrUrl)) {
            $token = $this->uploadFileFromUrl($tokenOrUrl, 'audio');
            $this->attachments[] = [
                'type' => 'audio',
                'payload' => [
                    'token' => $token
                ]
            ];
        } else {
            $this->attachments[] = [
                'type' => 'audio',
                'payload' => [
                    'token' => $tokenOrUrl
                ]
            ];
        }

        return $this;
    }

    /**
     * Добавляет местоположение
     *
     * @param float $latitude Широта
     * @param float $longitude Долгота
     * @return $this
     */
    public function location(float $latitude, float $longitude): self
    {
        $this->attachments[] = [
            'type' => 'location',
            'latitude' => $latitude,
            'longitude' => $longitude
        ];
        return $this;
    }

    /**
     * Добавляет контакт
     *
     * @param string $name Имя контакта
     * @param int $contactId ID контакта
     * @param string|null $vcfInfo VCF информация
     * @param string|null $vcfPhone VCF телефон
     * @return $this
     */
    public function contact(string $name, int $contactId, ?string $vcfInfo = null, ?string $vcfPhone = null): self
    {
        $attachment = [
            'type' => 'contact',
            'payload' => [
                'name' => $name,
                'contact_id' => $contactId
            ]
        ];

        if ($vcfInfo !== null) {
            $attachment['payload']['vcf_info'] = $vcfInfo;
        }

        if ($vcfPhone !== null) {
            $attachment['payload']['vcf_phone'] = $vcfPhone;
        }

        $this->attachments[] = $attachment;
        return $this;
    }

    /**
     * Проверяет, является ли строка URL
     *
     * @param string $string
     * @return bool
     */
    private function isUrl(string $string): bool
    {
        return filter_var($string, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Скачивает файл по URL во временный файл с использованием Guzzle
     *
     * @param string $url
     * @return string Путь к временному файлу
     * @throws GuzzleException
     */
    private function downloadFromUrl(string $url): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'max_upload_');

        $this->client = $this->client ?: new Client();

        $this->client->request('GET', $url, [
            'sink' => $tempFile,
            'timeout' => 300,
            'verify' => false
        ]);

        return $tempFile;
    }

    /**
     * Загружает файл из URL на сервер MAX
     *
     * @param string $url URL файла
     * @param string $type Тип файла (image, video, audio, file)
     * @return string Токен загруженного файла
     * @throws \Exception
     */
    private function uploadFileFromUrl(string $url, string $type): string
    {
        $max = \Yii::$app->max;

        if (!$max) {
            throw new \RuntimeException('Max component is not available in Yii::$app->max');
        }

        $tempFile = null;

        try {
            // Скачиваем файл во временную папку
            $tempFile = $this->downloadFromUrl($url);

            // Загружаем файл на сервер MAX
            $result = $max->upload($tempFile, $type);

            if (!isset($result['token'])) {
                throw new \Exception("Failed to upload file from URL: {$url}. No token received.");
            }

            return $result['token'];

        } finally {
            // Удаляем временный файл
            if ($tempFile && file_exists($tempFile)) {
                unlink($tempFile);
            }
        }
    }

    /**
     * Создает строку кнопок для клавиатуры
     *
     * @param array $buttons Массив кнопок
     * @return array
     */
    public static function row(array $buttons): array
    {
        return $buttons;
    }

    /**
     * Создает клавиатуру с несколькими строками
     *
     * @param array $rows Массив строк кнопок
     * @return array
     */
    public static function keyboard(array $rows): array
    {
        return $rows;
    }

    /**
     * Формирует итоговое сообщение для отправки
     *
     * @return array
     */
    public function build(): array
    {
        $result = $this->message;

        if (!empty($this->attachments)) {
            $result['attachments'] = $this->attachments;
        }

        return $result;
    }
}