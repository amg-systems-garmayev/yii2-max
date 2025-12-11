<?php

namespace garmayev\max\types\buttons;

use yii\base\Model;

/**
 * @property string $text
 * @property string $payload
 * @property string $intent
 */
class Callback extends Button
{
    private string $_text;
    private string $_payload;
    private ?string $_intent;

    /**
     * Токен кнопки
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
     * Намерение кнопки. Влияет на отображение клиентом.
     * @return string
     */
    public function getPayload(): string
    {
        return $this->_payload;
    }

    /**
     * @param string $payload
     * @return void
     */
    public function setPayload(string $payload): void
    {
        $this->_payload = $payload;
    }

    /**
     * @return string|null
     */
    public function getIntent(): ?string
    {
        return $this->_intent;
    }

    /**
     * @param string|null $intent
     * @return void
     */
    public function setIntent(?string $intent): void
    {
        $this->_intent = $intent;
    }
}