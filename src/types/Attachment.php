<?php

namespace garmayev\max\types;

use garmayev\max\types\payloads\ImagePayload;
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

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->_filename;
    }

    /**
     * @param $value
     * @return void
     */
    public function setFilename($value)
    {
        $this->_filename = $value;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->_size;
    }

    /**
     * @param $value
     * @return void
     */
    public function setSize($value)
    {
        $this->_size = $value;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->_latitude;
    }

    /**
     * @param $value
     * @return void
     */
    public function setLatitude($value)
    {
        $this->_latitude = $value;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->_longitude;
    }

    /**
     * @param $value
     * @return void
     */
    public function setLongitude($value)
    {
        $this->_longitude = $value;
    }

    /**
     * @return mixed
     */
    public function getWIdth()
    {
        return $this->_width;
    }

    /**
     * @param $value
     * @return void
     */
    public function setWidth($value)
    {
        $this->_width = $value;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->_height;
    }

    /**
     * @param $value
     * @return void
     */
    public function setHeight($value)
    {
        $this->_height = $value;
    }
}