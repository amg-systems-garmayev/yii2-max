<?php
namespace garmayev\max\types;

use garmayev\max\types\Request;
use garmayev\max\types\Response;
use garmayev\max\types\Update;
use GuzzleHttp\Client;
use yii\base\Component;

class MaxBase extends \yii\base\Component
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

    public function send($method, $action, $data)
    {
        $data = $this->client->request($method, $this->base.$action, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => $this->access_token,
            ],
            'body' => json_encode($data)
        ]);
        \Yii::error(json_decode($data->getBody()->getContents(), true));
        return new Response(json_decode($data->getBody()->getContents(), true));
    }
}