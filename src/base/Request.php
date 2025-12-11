<?php

namespace garmayev\max\types;

use yii\base\Model;

class Request extends Model
{
    private int $_timestamp;
    private Message $_message;
    private string $_user_locale;
    private string $_update_type;
    private string $_message_id;
    private $_chat_id;
    private $_user_id;
    private $_user;
    private array $_callback_data = [];


    public function rules()
    {
        return [
            [['_timestamp'], 'integer'],
            [['_user_locale', '_update_type'], 'string'],
            [['_message'], 'safe'],
            [['timestamp', 'message', 'user_locale', 'update_type'], 'safe'],
            [['_callback_data'], 'safe'],
        ];
    }

    public function getTimestamp(): int
    {
        return $this->_timestamp;
    }

    public function setTimestamp(int $timestamp): void
    {
        $this->_timestamp = $timestamp;
    }

    public function getMessage(): Message
    {
        return $this->_message;
    }

    public function setMessage(array $message): void
    {
        $this->_message = new Message($message);
    }

    public function getUser_locale(): string
    {
        return $this->_user_locale;
    }

    public function setUser_locale(string $user_locale): void
    {
        $this->_user_locale = $user_locale;
    }

    public function getUpdate_type(): string
    {
        return $this->_update_type;
    }

    public function setUpdate_type(string $update_type): void
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

    public function getUser()
    {
        return $this->_user;
    }

    public function setUser($value)
    {
        $this->_user = new User($value);
    }

    /**
     * @return array
     */
    public function getCallbackData(): array
    {
        return $this->_callback_data;
    }

    /**
     * @param array|string $value
     * @return void
     */
    public function setCallbackData($value): void
    {
        if (is_string($value)) {
            $value = json_decode($value, true) ?: [];
        }
        $this->_callback_data = is_array($value) ? $value : [];
    }
}