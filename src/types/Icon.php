<?php

namespace garmayev\max\types;

use yii\base\Model;

/**
 * @property string $url
 */
class Icon extends Model
{
    public string $_url;

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return void
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }
}