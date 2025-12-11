<?php

namespace garmayev\max\types;

use garmayev\max\types\payloads\ImagePayload;
use garmayev\max\types\payloads\Payload;
use yii\base\Model;
use garmayev\max\types\buttons\Callback;
use garmayev\max\types\buttons\Link;
use garmayev\max\types\buttons\RequestContact;
use garmayev\max\types\buttons\RequestGeoLocation;
use garmayev\max\types\buttons\Message;

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
    const TYPE_INLINE_KEYBOARD = "inline_keyboard";

    private string $_type;
    private array $_payload;
    private string $_filename;
    private int $_size;
    private float $_latitude;
    private float $_longitude;
    private int $_width;
    private int $_height;
    public $callback_id;

    public function __construct($data)
    {
        $this->type = $data['type'];
        if (isset($data['payload'])) {
            $this->payload = $data['payload'];
        }
        if (isset($data['latitude'])) {
            $this->latitude = $data['latitude'];
            $this->longitude = $data['longitude'];
        }
    }

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
        switch ($this->type) {
            case self::TYPE_INLINE_KEYBOARD:
                foreach ($payload['buttons'] as $row) 
                {
                    foreach ($row as $item) {
//                        \Yii::error($item);
                        switch ($item['type']) {
                            case 'link':
                                $this->_payload[] = new Link($item);
                                break;
                            case 'callback':
                                $this->_payload[] = new Callback($item);
                                break;
                            case 'link':
                                $this->_payload[] = new RequestContact($item);
                                break;
                            case 'link':
                                $this->_payload[] = new RequestGeoLocation($item);
                                break;
                            case 'link':
                                $this->_payload[] = new Message($item);
                                break;
                        }
                    }
                }
                break;
        }
//        foreach ($payload as $item) {
//            $this->_payload[] = new Payload($payload);
//        }
    }

    /**
     * @return string
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
     * @return int
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
     * @return float
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
     * @return float
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
     * @return int
     */
    public function getWidth()
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
     * @return int
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