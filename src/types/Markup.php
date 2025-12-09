<?php

namespace garmayev\max\types;

use yii\base\Model;

/**
 * @property string $type
 * @property int $from
 * @property int $length
 */
class Markup extends Model
{
    public string $_type;
    public int $_from;
    public int $_length;

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
     * @return int
     */
    public function getFrom(): int
    {
        return $this->_from;
    }

    /**
     * @param int $from
     * @return void
     */
    public function setFrom(int $from): void
    {
        $this->_from = $from;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->_length;
    }

    /**
     * @param int $length
     * @return void
     */
    public function setLength(int $length): void
    {
        $this->_length = $length;
    }
}