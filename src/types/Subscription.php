<?php

namespace garmayev\max\types;

use yii\base\Model;

class Subscription extends Model
{
    private string $_url;
    private int $_time;
    private array $_update_types;

    public function getUrl(): string
    {
        return $this->_url;
    }

    public function setUrl(string $url): void
    {
        $this->_url = $url;
    }

    public function getTime(): int
    {
        return $this->_time;
    }

    public function setTime(int $time): void
    {
        $this->_time = $time;
    }

    public function getUpdateTypes(): array
    {
        return $this->_update_types;
    }

    public function setUpdateTypes(array $update_types): void
    {
        $this->_update_types = $update_types;
    }
}