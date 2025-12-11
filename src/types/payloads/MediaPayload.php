<?php

namespace garmayev\max\types\payloads;

use yii\base\Model;

class MediaPayload extends Model
{
    private ?string $_token;

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->_token;
    }

    /**
     * @param string|null $token
     * @return void
     */
    public function setToken(?string $token): void
    {
        $this->_token = $token;
    }
}