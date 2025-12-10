<?php

namespace garmayev\max;

use garmayev\max\types\Update;
use garmayev\max\types\MaxBase;

/**
 * @property string $access_token
 * @property string $secret
 */
class Max extends MaxBase
{
    public string $access_token;
    public string $secret;
    private string $base = "https://platform-api.max.ru/";

    public function init()
    {
        parent::init();
    }

    public function setWebhook(string $url)
    {
        return $this->send('POST', 'subscriptions', [
                'url' => $url,
                'update_types' => [Update::TYPE_MESSAGE_CREATED, Update::TYPE_MESSAGE_REMOVED, Update::TYPE_BOT_STARTED],
                'secret' => $this->secret
            ]);
    }

    public function sendMessage($user_id, $params)
    {
//        \Yii::error(get_class_methods(MaxBase::class));
        return parent::send('POST', "messages?user_id={$user_id}", $params);
    }
}