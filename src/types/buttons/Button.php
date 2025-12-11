<?php

namespace garmayev\max\types\buttons;

use yii\base\Model;

/**
 * @property string $type
 */
class Button extends Model
{
    const TYPE_CALLBACK = 'callback';
    const TYPE_LINK = 'link';
    const TYPE_REQUEST_GEO_LOCATION = 'request_geo_location';
    const TYPE_REQUEST_CONTACT = 'request_contact';
    const TYPE_OPEN_APP = 'open_app';
    const TYPE_MESSAGE = 'message';

    private string $_type;

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
}