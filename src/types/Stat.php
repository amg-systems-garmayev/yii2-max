<?php

namespace garmayev\max\types;

use yii\base\Model;

/**
 * @property int $views
 */
class Stat extends Model
{
    public int $_views;

    /**
     * @return int
     */
    public function getViews(): int
    {
        return $this->_views;
    }

    /**
     * @param int $views
     * @return void
     */
    public function setViews(int $views): void
    {
        $this->_views = $views;
    }
}