<?php

namespace garmayev\max\types;

use yii\base\Model;

/**
 * @property integer $photo_id
 * @property string $token
 * @property string $url
 */
class Payload extends Model
{
    public $_photo_id;
    public $_token;
    public $_url;

    /**
     * @return int
     */
    public function getPhoto_id(): int
    {
        return $this->_photo_id;
    }

    /**
     * @param mixed $photo_id
     */
    public function setPhoto_id($photo_id): void
    {
        $this->_photo_id = $photo_id;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->_token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->_token = $token;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->_url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url): void
    {
        $this->_url = $url;
    }
}