<?php

namespace garmayev\max\types;

use yii\base\Model;

/**
 * Класс для обработки ответов от API MAX
 */
class Response extends Model
{
    public bool $success;
    public ?array $data;
    public ?string $error;
    public ?array $message;
    public ?int $chat_id;
    public ?string $chat_type;
    public ?string $status;

    /**
     * Конструктор
     *
     * @param array $data Данные ответа
     */
    public function __construct(array $data)
    {
        parent::__construct();

        $this->success = $data['success'] ?? false;
        $this->data = $data['data'] ?? null;
        $this->error = $data['error'] ?? null;
        $this->message = $data['message'] ?? null;
        $this->chat_id = $data['chat_id'] ?? null;
        $this->chat_type = $data['chat_type'] ?? null;
        $this->status = $data['status'] ?? null;
    }

    /**
     * Проверяет, успешен ли ответ
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success === true;
    }

    /**
     * Получает ID сообщения из ответа
     *
     * @return string|null
     */
    public function getMessageId(): ?string
    {
        return $this->message['mid'] ?? $this->data['mid'] ?? null;
    }

    /**
     * Получает текст сообщения из ответа
     *
     * @return string|null
     */
    public function getMessageText(): ?string
    {
        return $this->message['text'] ?? $this->data['text'] ?? null;
    }

    /**
     * Получает текст ошибки
     *
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }
}