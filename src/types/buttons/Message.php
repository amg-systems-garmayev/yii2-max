<?php

namespace garmayev\max\types\buttons;

use garmayev\max\types\buttons\Button;

/**
 * @property string $text
 */
class Message extends Button
{
    private string $_text;

    /**
     * Текст кнопки, который будет отправлен в чат от лица пользователя
     * @return string
     */
    public function getText(): string
    {
        return $this->_text;
    }

    /**
     * @param string $text
     * @return void
     */
    public function setText(string $text): void
    {
        $this->_text = $text;
    }
}