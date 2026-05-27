<?php

namespace garmayev\max\types\payloads;

use yii\base\Model;

class ImagePayload extends Payload
{
    private ?string $_url;
    private ?string $_token;
    private ?array $_photos;
  	public $photo_id;

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->_url;
    }

    /**
     * @param string|null $url
     * @return void
     */
    public function setUrl(?string $url): void
    {
        $this->_url = $url;
    }

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

    /**
     * @return array|null
     */
    public function getPhotos(): ?array
    {
        return $this->_photos;
    }

    /**
     * @param array|null $photos
     * @return void
     */
    public function setPhotos(?array $photos): void
    {
        $this->_photos = $photos;
    }
}