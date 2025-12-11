<?php

namespace garmayev\max;

use garmayev\max\types\Update;
use garmayev\max\base\MaxBase;
use garmayev\max\CallbackManager;

/**
 * @property string $access_token
 * @property string $secret
 */
class Max extends MaxBase
{
    public string $access_token;
    public string $secret;

    public function setWebhook(string $url, arra $types)
    {
        return parent::send('POST', 'subscriptions', [
            'url' => $url,
            'update_types' => $types,
            'secret' => $this->secret,
        ]);
    }

    public function sendMessage($args, $params)
    {
        return parent::send('POST', "messages", $params, $args);
    }

    public function editMessage($args, $params)
    {
        return parent::send();
    }

    public function answers($args, $params)
    {
        return parent::send('POST', 'answers', $params, $args);
    }
}