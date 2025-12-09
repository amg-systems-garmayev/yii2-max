<?php

namespace garmayev\max;

use garmayev\max\types\Request;
use garmayev\max\types\Response;
use garmayev\max\types\Update;
use GuzzleHttp\Client;
use yii\base\Component;

/**
 * @property string $access_token
 * @property string $secret
 */
class Max extends Component
{
    public string $access_token;
  	public string $secret;
    private string $base = "https://platform-api.max.ru/";
    private Client $client;
    public Request $request;
    public Response $response;

    public function init()
    {
        $this->client = new Client();
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data) {
          	\Yii::error($data);
            $this->request = new Request($data);
        }
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
                'secret' => $this->secret
            ])
        ]);
        $this->response = new Response($response);
    }
}