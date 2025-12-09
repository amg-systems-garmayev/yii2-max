<?php

namespace garmayev\max;

use GuzzleHttp\Client;
use yii\base\Component;
use garmayev\max\Update;

class Max extends Component
{
    public string $access_token;
    private string $base;
    private Client $client;

    public function setWebhook(string $url)
    {
        $response = $this->client->request('POST', $url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => $this->access_token,
            ],
            'body' => json_encode([
                'url' => $url,
                'update_types' => [Update::TYPE_MESSAGE_CREATED, Update::TYPE_BOT_STARTED],
                'secret' => ''
            ])
        ]);
    }
}