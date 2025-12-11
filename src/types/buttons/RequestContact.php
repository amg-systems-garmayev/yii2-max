<?php

namespace garmayev\max\types\buttons;

/**
 * @property string $text
 */
class RequestContact extends Button
{
    private string $_text;

    /**
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