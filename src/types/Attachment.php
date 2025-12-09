<?php

namespace garmayev\max\types;

use yii\base\Model;

/**
 * @property string $type
 * @property Payload $payload
 */
class Attachment extends Model
{
    public string $_type;
    public Payload $_payload;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->_type;
    }

    /**
     * @param string $type
     * @return void
     */
    public function setType(string $type): void
    {
        $this->_type = $type;
    }

    /**
     * @return Payload
     */
    public function getPayload(): Payload
    {
        return $this->_payload;
    }

    /**
     * @param Payload $payload
     * @return void
     */
    public function setPayload(Payload $payload): void
    {
        $this->_payload = new Payload($payload);
    }
}