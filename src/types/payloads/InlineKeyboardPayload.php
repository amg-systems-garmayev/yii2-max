<?php

namespace garmayev\max\types\payloads;

use garmayev\max\types\buttons\Button;
use garmayev\max\types\buttons\Callback;
use garmayev\max\types\buttons\Link;
use garmayev\max\types\buttons\Message;
use garmayev\max\types\buttons\OpenApp;
use garmayev\max\types\buttons\RequestContact;
use garmayev\max\types\buttons\RequestGeoLocation;
use yii\base\Model;

class InlineKeyboardPayload extends Payload
{
    private array $_buttons;

    public function getButtons(): array
    {
        return $this->_buttons;
    }

    public function setButtons(array $buttons): void
    {
        foreach ($buttons as $button) {
            switch ($button['type']) {
                case Button::TYPE_CALLBACK:
                    $this->_buttons[] = new Callback($buttons);
                    break;
                case Button::TYPE_LINK:
                    $this->_buttons[] = new Link($buttons);
                    break;
                case Button::TYPE_MESSAGE:
                    $this->_buttons[] = new Message($buttons);
                    break;
                case Button::TYPE_OPEN_APP:
                    $this->_buttons[] = new OpenApp($buttons);
                    break;
                case Button::TYPE_REQUEST_CONTACT:
                    $this->_buttons[] = new RequestContact($buttons);
                    break;
                case Button::TYPE_REQUEST_GEO_LOCATION:
                    $this->_buttons[] = new RequestGeoLocation($buttons);
                    break;
            }
        }
    }
}