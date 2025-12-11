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
}