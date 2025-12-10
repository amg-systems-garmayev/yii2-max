<?php

namespace garmayev\max\types;

use yii\base\Model;

/**
 * @property string $type
 * @property Payload $payload
 */
class Attachment extends Model
{
    const TYPE_LOCATION = "location";
    const TYPE_CONTACT = "contact";
    const TYPE_IMAGE = "image";
    const TYPE_STICKER = "sticker";
    const TYPE_FILE = "file";

    private string $_type;
    private array $_payload;
    private $_filename;
    private $_size;
    private $_latitude;
    private $_longitude;
    private $_width;
    private $_height;

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
     * @return array
     */
    public function getPayload(): array
    {
        return $this->_payload;
    }

    /**
     * @param Payload $payload
     * @return void
     */
    public function setPayload(array $payload): void
    {
        foreach ($payload as $item) {
            $this->_payload[] = new Payload($payload);
        }
    }

    public function getFilename()
    {
        return $this->_filename;
    }

    public function setFilename($value)
    {
        $this->_filename = $value;
    }

    public function getSize()
    {
        return $this->_size;
    }

    public function setSize($value)
    {
        $this->_size = $value;
    }

    public function getLatitude()
    {
        return $this->_latitude;
    }

    public function setLatitude($value)
    {
        $this->_latitude = $value;
    }

    public function getLongitude()
    {
        return $this->_longitude;
    }

    public function setLongitude($value)
    {
        $this->_longitude = $value;
    }

    public function getWIdth()
    {
        return $this->_width;
    }

    public function setWidth($value)
    {
        $this->_width = $value;
    }

    public function getHeight()
    {
        return $this->_height;
    }

    public function setHeight($value)
    {
        $this->_height = $value;
    }
}