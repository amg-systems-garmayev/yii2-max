<?php

namespace garmayev\max;

use garmayev\max\types\Message;
use yii\base\Component;

class CallbackManager extends Component
{
    /**
     * @var CallbackHandlerInterface[] Зарегистрированные обработчики
     */
    private array $handlers = [];

    /**
     * Регистрирует обработчик
     */
    public function registerHandler(CallbackHandlerInterface $handler): void
    {
        $this->handlers[] = $handler;
    }

    /**
     * Регистрирует несколько обработчиков
     */
    public function registerHandlers(array $handlers): void
    {
        foreach ($handlers as $handler) {
            $this->registerHandler($handler);
        }
    }

    /**
     * Обрабатывает callback сообщение
     */
    public function handle(Message $message): bool
    {
        if (!$message->isCallback()) {
            return false;
        }

        $callbackData = json_encode($message->getCallbackData());

        foreach ($this->handlers as $handler) {
            if ($handler->canHandle($callbackData)) {
                $handler->handle($message, $message->getCallbackData());
                return true;
            }
        }

        return false;
    }

    /**
     * Получает все зарегистрированные обработчики
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }
}