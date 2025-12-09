<?php

namespace garmayev\max;

use garmayev\max\types\Update;
use GuzzleHttp\Client;
use yii\base\Component;

class Max extends Component
{
    public string $access_token;
    private string $base = "https://platform-api.max.ru/";
    private Client $client;

    public function init()
    {
        $this->client = new Client();
    }

    public function setWebhook(string $url)
    {
        $response = $this->client->request('POST', $this->base."subscriptions", [
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
        \Yii::error($response->getBody()->getContents());
    }
}