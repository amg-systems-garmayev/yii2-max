<?php

namespace garmayev\max\types\payloads;

use yii\base\Model;

class StickerPayload extends Model
{
    private string $_code;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->_code;
    }

    /**
     * @param string $code
     * @return void
     */
    public function setCode(string $code): void
    {
        $this->_code = $code;
    }
}