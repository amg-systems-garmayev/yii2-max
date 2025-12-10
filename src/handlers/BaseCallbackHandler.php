<?php

namespace garmayev\max\handlers;

use garmayev\max\CallbackHandlerInterface;
use garmayev\max\types\Message;

abstract class BaseCallbackHandler implements CallbackHandlerInterface
{
    /**
     * @var string Префикс для идентификации обработчика
     */
    protected string $prefix;

    public function canHandle(string $callbackData): bool
    {
        $data = json_decode($callbackData, true);
        return is_array($data) && isset($data['handler']) && $data['handler'] === $this->prefix;
    }

    /**
     * Создает callback данные для кнопки
     */
    public function createCallbackData(array $params = []): string
    {
        return json_encode(array_merge(['handler' => $this->prefix], $params));
    }

    abstract public function handle(Message $message, array $callbackData): void;
}