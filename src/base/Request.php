<?php

namespace garmayev\max\base;

use yii\base\Model;
use garmayev\max\types\Callback;
use garmayev\max\types\Message;
use garmayev\max\types\User;

class Request extends Model
{
    private ?int $_timestamp = null;
    private ?Message $_message = null;
    private ?string $_user_locale = null;
    private ?string $_update_type = null;
    private ?string $_message_id = null;
    private $_chat_id = null;
    private $_user_id = null;
    private ?User $_user = null;
    private ?Callback $_callback = null;

    public function rules()
    {
        return [
            [['_timestamp'], 'integer'],
            [['_user_locale', '_update_type', '_message_id'], 'string'],
            [['_message', '_callback', '_user', '_chat_id'], 'safe'],
            [['timestamp', 'message', 'user_locale', 'update_type', 'callback'], 'safe'],
        ];
    }

    /**
     * @param array $data
     */
    public function __construct(array $data, $config = [])
    {
        parent::__construct($config);
        $this->load($data, '');
    }

    public function getTimestamp(): ?int
    {
        return $this->_timestamp;
    }

    public function setTimestamp(?int $timestamp): void
    {
        $this->_timestamp = $timestamp;
    }

    public function getMessage(): ?Message
    {
        return $this->_message;
    }

    public function setMessage(?array $message): void
    {
        if ($message !== null) {
            $this->_message = new Message($message);
        }
    }

    public function getUser_locale(): ?string
    {
        return $this->_user_locale;
    }

    public function setUser_locale(?string $user_locale): void
    {
        $this->_user_locale = $user_locale;
    }

    public function getUpdate_type(): ?string
    {
        return $this->_update_type;
    }

    public function setUpdate_type(?string $update_type): void
    {
        $this->_update_type = $update_type;
    }

    public function getMessage_id()
    {
        return $this->_message_id;
    }

    public function setMessage_id($value)
    {
        $this->_message_id = $value;
    }

    public function getChat_id()
    {
        return $this->_chat_id;
    }

    public function setChat_id($value)
    {
        $this->_chat_id = $value;
    }

    public function getUser_id()
    {
        return $this->_user_id;
    }

    public function setUser_id($value)
    {
        $this->_user_id = $value;
    }

    public function getUser(): ?User
    {
        return $this->_user;
    }

    public function setUser($value)
    {
        if ($value !== null) {
            $this->_user = new User($value);
        }
    }

    public function getCallback(): ?Callback
    {
        return $this->_callback;
    }

    public function setCallback($value)
    {
        if ($value !== null) {
            $this->_callback = new Callback($value);
        }
    }
}