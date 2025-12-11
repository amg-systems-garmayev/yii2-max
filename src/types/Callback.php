<?php

namespace garmayev\max\types;

use yii\base\Model;

/**
 * @property string $url
 */
class Callback extends Model
{
    private int $_timestamp;
    private string $_callback_id;
    private string $_payload;
    private User $_user;

    public function getTimestamp()
    {
        return $this->_timestamp;
    }

    public function setTimestamp($value)
    {
        $this->_timestamp = $value;
    }

    public function getCallback_id()
    {
        return $this->_callback_id;
    }

    public function setCallback_id($value)
    {
        $this->_callback_id = $value;
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function setPayload($value)
    {
        $this->_payload = $value;
    }

    public function getUser()
    {
        return $this->_user;
    }

    public function setUser($value)
    {
        $this->_user = new User($value);
    }
}