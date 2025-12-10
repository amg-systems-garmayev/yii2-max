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
    public $_fileId;
    public $_photo_id;
    public $_token;
    public $_url;
    public $_vcf_info;
    private $_max_info;
    private $_code;

    /**
     * @return mixed
     */
    public function getFileId()
    {
        return $this->_fileId;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function setFileId($value)
    {
        return $this->_fileId;
    }

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

    /**
     * @return mixed
     */
    public function getVsf_info()
    {
        return $this->_vcf_info;
    }

    /**
     * @param $value
     * @return void
     */
    public function setVcf_info($value)
    {
        $this->_vcf_info = $value;
    }

    /**
     * @return mixed
     */
    public function getMax_info()
    {
        return $this->_max_info;
    }

    /**
     * @param $value
     * @return void
     */
    public function setMax_info($value)
    {
        $this->_max_info = $value;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->_code;
    }

    /**
     * @param $value
     * @return void
     */
    public function setCode($value)
    {
        $this->_code = $value;
    }
}