<?php

namespace garmayev\max\types\buttons;

/**
 * @property string $text
 * @property bool $quick
 */
class RequestGeoLocation extends Button
{
    private string $_text;
    private bool $_quick;

    /**
     * Видимый текст кнопки
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

    /**
     * Если true, отправляет местоположение без запроса подтверждения пользователя
     * @return bool
     */
    public function getQuick(): bool
    {
        return $this->_quick;
    }

    /**
     * @param bool $quick
     * @return void
     */
    public function setQuick(bool $quick): void
    {
        $this->_quick = $quick;
    }
}