<?php

namespace garmayev\max;

use garmayev\max\types\Update;
use garmayev\max\base\MaxBase;
use garmayev\max\CallbackManager;

/**
 * @property string $access_token
 * @property string $secret
 * @property CallbackManager $callbackManager
 */
class Max extends MaxBase
{
    public string $access_token;
    public string $secret;
    private string $base = "https://platform-api.max.ru/";

    /**
     * @var CallbackManager Менеджер обработки callback
     */
    private CallbackManager $_callbackManager;

    public function init()
    {
        parent::init();
        $this->_callbackManager = new CallbackManager();
    }

    public function setWebhook(string $url)
    {
        return $this->send('POST', 'subscriptions', [
            'url' => $url,
            'update_types' => [
                Update::TYPE_MESSAGE_CREATED,
                Update::TYPE_MESSAGE_CALLBACK, // Добавляем обработку callback
                Update::TYPE_MESSAGE_REMOVED,
                Update::TYPE_BOT_STARTED
            ],
            'secret' => $this->secret
        ]);
    }

    public function sendMessage($user_id, $params)
    {
        return parent::send('POST', "messages?user_id={$user_id}", $params);
    }

    /**
     * Получить менеджер обработки callback
     */
    public function getCallbackManager(): CallbackManager
    {
        return $this->_callbackManager;
    }

    /**
     * Обработать входящее обновление
     */
    public function processUpdate(): bool
    {
        if (!$this->request) {
            return false;
        }

        // Если это callback (нажатие кнопки)
        if ($this->request->getUpdate_type() === Update::TYPE_MESSAGE_CALLBACK) {
            return $this->_callbackManager->handle($this->request->getMessage());
        }

        return false;
    }

    /**
     * Создать inline клавиатуру с кнопками
     */
    public function createInlineKeyboard(array $buttons): array
    {
        return [
            'inline_keyboard' => $buttons
        ];
    }

    /**
     * Создать callback кнопку
     */
    public function createCallbackButton(string $text, array $callbackData, array $options = []): array
    {
        return array_merge([
            'text' => $text,
            'callback_data' => json_encode($callbackData),
        ], $options);
    }
}