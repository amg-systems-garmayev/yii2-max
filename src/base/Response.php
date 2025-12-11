<?php

namespace garmayev\max\base;

use yii\base\Model;
use garmayev\max\types\Message;

class Response extends Model
{
    private bool $_success;
    private Message $_message;
    private int $_chat_id;
    private string $_chat_type;
    private string $_status;

    public function rules()
    {
        return [
            [['_success', '_message', '_chat_id', '_chat_type', '_status'], 'safe']
        ];
    }

    public function getSuccess()
    {
        return $this->_success;
    }

    public function setSuccess($value)
    {
        $this->_success = $value;
    }

    public function getMessage()
    {
        return $this->_message;
    }

    public function setMessage($value)
    {
        $this->_message = new Message($value);
    }

    public function getChat_id()
    {
        return $this->_chat_id;
    }

    public function setChat_id($value)
    {
        $this->_chat_id = $value;
    }

    public function getStatus()
    {
        return $this->_status;
    }

    public function setStatus($value)
    {
        $this->_status = $value;
    }
}