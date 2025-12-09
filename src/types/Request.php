<?php

namespace garmayev\max\types;

use yii\base\Model;

class Request extends Model
{
    private int $_timestamp;
    private Message $_message;
    private string $_user_locale;
    private string $_update_type;

    public function rules()
    {
        return [
            [['_timestamp'], 'integer'],
            [['_user_locale', '_update_type'], 'string'],
            [['_message'], 'safe'],
            [['timestamp', 'message', 'user_locale', 'update_type'], 'safe'],
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

    public function setMessage(Message $message): void
    {
        $this->_message = $message;
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
}