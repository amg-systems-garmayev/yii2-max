<?php

namespace garmayev\max\types;

use garmayev\max\types\buttons\Button;
use garmayev\max\types\payloads\ImagePayload;
use garmayev\max\types\payloads\Payload;
use yii\base\Model;
use garmayev\max\types\buttons\Callback;
use garmayev\max\types\buttons\Link;
use garmayev\max\types\buttons\RequestContact;
use garmayev\max\types\buttons\RequestGeoLocation;
use garmayev\max\types\buttons\Message;
use garmayev\max\types\payloads\ContactPayload;

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
            $this->setPayload($data['payload']);
        }
        if (isset($data['latitude'])) {
            $this->setLatitude($data['latitude']);
            $this->setLongitude($data['longitude']);
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
     * @param array $payload
     * @return void
     */
    public function setPayload(array $payload): void
    {
        switch ($this->type) {
            case self::TYPE_INLINE_KEYBOARD:
                foreach ($payload['buttons'] as $row) 
                {
                    foreach ($row as $item) {
                        switch ($item['type']) {
                            case Button::TYPE_LINK:
                                $this->_payload['buttons'][] = new Link($item);
                                break;
                            case Button::TYPE_CALLBACK:
                                $this->_payload['buttons'][] = new Callback($item);
                                break;
                            case Button::TYPE_REQUEST_CONTACT:
                                $this->_payload['buttons'][] = new RequestContact($item);
                                break;
                            case Button::TYPE_REQUEST_GEO_LOCATION:
                                $this->_payload['buttons'][] = new RequestGeoLocation($item);
                                break;
                            case Button::TYPE_MESSAGE:
                                $this->_payload['buttons'][] = new Message($item);
                                break;
                        }
                    }
                }
                break;
            case self::TYPE_CONTACT:
                $this->_payload[] = new ContactPayload($payload['max_info']);
                break;
//            case self::TYPE_LOCATION:
//                $this->_payload[] = new LocationPayload();
        }
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