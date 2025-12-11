<?php

namespace garmayev\max;

use garmayev\max\types\Update;
use garmayev\max\base\MaxBase;

/**
 * @property string $access_token
 * @property string $secret
 */
class Max extends MaxBase
{
    public string $access_token;
    public string $secret;

    public function setWebhook(string $url, array $types)
    {
        return parent::send('POST', 'subscriptions', [
            'url' => $url,
            'update_types' => $types,
            'secret' => $this->secret,
        ]);
    }

    public function sendMessage(array $args, array $params)
    {
        return parent::send('POST', "messages", $params, $args);
    }

    public function editMessage(array $args, array $params)
    {
        return parent::send('PUT', 'messages', $params, $args);
    }

    public function answers(array $args, array $params)
    {
        return parent::send('POST', 'answers', $params, $args);
    }
}