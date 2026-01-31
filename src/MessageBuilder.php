<?php

namespace garmayev\max;

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
     * Устанавливает chat_id
     *
     * @param int $chatId ID чата
     * @return $this
     */
    public function chatId(int $chatId): self
    {
        $this->message['chat_id'] = $chatId;
        return $this;
    }

    /**
     * Устанавливает chat_type
     *
     * @param string $chatType Тип чата
     * @return $this
     */
    public function chatType(string $chatType): self
    {
        $this->message['chat_type'] = $chatType;
        return $this;
    }

    /**
     * Устанавливает user_id
     *
     * @param int $userId ID пользователя
     * @return $this
     */
    public function userId(int $userId): self
    {
        $this->message['user_id'] = $userId;
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
     *
     * @param string $token Токен изображения
     * @param string|null $filename Имя файла
     * @param int|null $width Ширина
     * @param int|null $height Высота
     * @return $this
     */
    public function image(string $token, ?string $filename = null, ?int $width = null, ?int $height = null): self
    {
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
     *
     * @param string $token Токен файла
     * @param string $filename Имя файла
     * @param int $size Размер файла
     * @return $this
     */
    public function file(string $token, string $filename, int $size): self
    {
        $this->attachments[] = [
            'type' => 'file',
            'payload' => [
                'token' => $token
            ],
            'filename' => $filename,
            'size' => $size
        ];
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

    /**
     * Быстрый метод для создания сообщения с текстом
     *
     * @param string $text Текст сообщения
     * @param int $chatId ID чата
     * @return array
     */
    public static function textOnly(string $text, int $chatId): array
    {
        return self::create($text)->chatId($chatId)->build();
    }

    /**
     * Быстрый метод для создания сообщения с клавиатурой
     *
     * @param string $text Текст сообщения
     * @param int $chatId ID чата
     * @param array $keyboard Клавиатура
     * @param bool $isOneTime Одноразовая клавиатура
     * @return array
     */
    public static function withKeyboard(string $text, int $chatId, array $keyboard, bool $isOneTime = false): array
    {
        return self::create($text)
            ->chatId($chatId)
            ->inlineKeyboard($keyboard, $isOneTime)
            ->build();
    }

    /**
     * Быстрый метод для создания сообщения с изображением
     *
     * @param string $text Текст сообщения
     * @param int $chatId ID чата
     * @param string $imageToken Токен изображения
     * @return array
     */
    public static function withImage(string $text, int $chatId, string $imageToken): array
    {
        return self::create($text)
            ->chatId($chatId)
            ->image($imageToken)
            ->build();
    }
}