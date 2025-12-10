<?php

namespace garmayev\max;

use garmayev\max\types\Message;

interface CallbackHandlerInterface
{
    /**
     * Проверяет, может ли этот обработчик обработать данные callback
     */
    public function canHandle(string $callbackData): bool;

    /**
     * Обрабатывает нажатие кнопки
     */
    public function handle(Message $message, array $callbackData): void;
}