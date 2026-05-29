<?php

namespace garmayev\max\types;

use yii\base\Model;

/**
 * Класс для обработки ответов от API MAX
 */
class Response extends Model
{
    private bool $_success;
    private ?array $_data;
    private ?string $_error;
    private Message|array|string|null $_message = "";
    private ?int $_chat_id;
    private ?string $_chat_type;
    private ?string $_status;
    private ?array $_subscriptions;
    private ?array $_recipient;
    private ?array $_body;
    
    // Новые поля для загрузки файлов
    private ?string $_url;
    private ?string $_token;
    
    /**
     * Конструктор
     *
     * @param array $data Данные ответа
     */
    public function __construct(array $data)
    {
        parent::__construct();

        $this->_success = $data['success'] ?? false;
        $this->_data = $data['data'] ?? null;
        $this->_error = $data['error'] ?? null;
        $this->_message = $data['message'] ?? null;
        $this->_chat_id = $data['chat_id'] ?? null;
        $this->_chat_type = $data['chat_type'] ?? null;
        $this->_status = $data['status'] ?? null;
        $this->_subscriptions = $data['subscriptions'] ?? [];
        $this->_recipient = $data['recipient'] ?? [];
        $this->_body = $data['body'] ?? [];
        
        // Инициализация полей для загрузки файлов
        $this->_url = $data['url'] ?? null;
        $this->_token = $data['token'] ?? null;
    }

    /**
     * Проверяет, успешен ли ответ
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->_success === true;
    }

    /**
     * Получает ID сообщения из ответа
     *
     * @return string|null
     */
    public function getMessageId(): ?string
    {
        return $this->_message['mid'] ?? $this->_data['mid'] ?? null;
    }

    /**
     * Получает текст сообщения из ответа
     *
     * @return string|null
     */
    public function getMessageText(): ?string
    {
        return $this->_message['text'] ?? $this->_data['text'] ?? null;
    }

    /**
     * Получает текст ошибки
     *
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->_error;
    }

    public function getData(): ?array
    {
        return $this->_data;
    }

    public function setData(?array $data): void
    {
        $this->_data = $data;
    }

    public function getMessage(): ?array
    {
        return $this->_message;
    }

    public function setMessage(array|string $message): void
    {
        if (is_string($message)) {
            $this->_message = $message;
        } else {
            $this->_message = new Message($message);
        }
    }

    public function getChatId(): ?int
    {
        return $this->_chat_id;
    }

    public function setChatId(?int $chat_id): void
    {
        $this->_chat_id = $chat_id;
    }

    public function getChatType(): ?string
    {
        return $this->_chat_type;
    }

    public function setChatType(?string $chat_type): void
    {
        $this->_chat_type = $chat_type;
    }

    public function getStatus(): ?string
    {
        return $this->_status;
    }

    public function setStatus(?string $status): void
    {
        $this->_status = $status;
    }

    public function getSubscriptions(): ?array
    {
        return $this->_subscriptions;
    }

    public function setSubscriptions(?array $subscriptions): void
    {
        $this->_subscriptions = $subscriptions;
    }

    public function getRecipient(): ?array
    {
        return $this->_recipient;
    }

    public function setRecipient(?array $recipient): void
    {
        $this->_recipient = $recipient;
    }

    public function getBody(): ?array
    {
        return $this->_body;
    }

    public function setBody(?array $body): void
    {
        $this->_body = $body;
    }

    /**
     * Получает URL для загрузки файла
     *
     * @return string|null
     */
    public function getUrl(): ?string
    {
        // Сначала проверяем прямое поле url
        if ($this->_url !== null) {
            return $this->_url;
        }
        
        // Затем проверяем в data
        if (is_array($this->_data) && isset($this->_data['url'])) {
            return $this->_data['url'];
        }
        
        return null;
    }

    /**
     * Устанавливает URL для загрузки файла
     *
     * @param string|null $url
     */
    public function setUrl(?string $url): void
    {
        $this->_url = $url;
    }

    /**
     * Получает токен файла (для видео/аудио)
     *
     * @return string|null
     */
    public function getToken(): ?string
    {
        // Сначала проверяем прямое поле token
        if ($this->_token !== null) {
            return $this->_token;
        }
        
        // Затем проверяем в data
        if (is_array($this->_data) && isset($this->_data['token'])) {
            return $this->_data['token'];
        }
        
        return null;
    }

    /**
     * Устанавливает токен файла
     *
     * @param string|null $token
     */
    public function setToken(?string $token): void
    {
        $this->_token = $token;
    }

    /**
     * Магический метод для доступа к свойствам
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        $method = 'get' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method();
        }
        
        return parent::__get($name);
    }

    /**
     * Магический метод для проверки существования свойства
     *
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        $method = 'get' . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method() !== null;
        }
        
        return parent::__isset($name);
    }
}