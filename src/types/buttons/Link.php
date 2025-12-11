<?php

namespace garmayev\max\types\buttons;

/**
 * @property string $text
 * @property string $url
 */
class Link extends Button
{
    private string $_text;
    private string $_url;

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
     * @return string
     */
    public function getUrl(): string
    {
        return $this->_url;
    }

    /**
     * @param string $url
     * @return void
     */
    public function setUrl(string $url): void
    {
        $this->_url = $url;
    }
}