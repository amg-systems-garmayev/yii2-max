<?php

namespace garmayev\max\types\buttons;

use garmayev\max\types\buttons\Button;

class OpenApp extends Button
{
    private string $_text;
    private ?string $_web_app;
    private ?int $_user_id;
    private ?string $_payload;

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
     * Публичное имя (username) бота или ссылка на него, чьё мини-приложение надо запустить
     * @return string|null
     */
    public function getWeb_app(): ?string
    {
        return $this->_web_app;
    }

    /**
     * @param string|null $web_app
     * @return void
     */
    public function setWeb_app(?string $web_app): void
    {
        $this->_web_app = $web_app;
    }

    /**
     * Идентификатор бота, чьё мини-приложение надо запустить
     * @return int|null
     */
    public function getUser_id(): ?int
    {
        return $this->_user_id;
    }

    /**
     * @param int|null $user_id
     * @return void
     */
    public function setUser_id(?int $user_id): void
    {
        $this->_user_id = $user_id;
    }

    /**
     * Параметр запуска, который будет передан в initData мини-приложения
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
}