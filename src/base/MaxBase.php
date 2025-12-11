<?php
namespace garmayev\max\base;

use garmayev\max\types\Update;
use GuzzleHttp\Client;

/**
 * @property string $access_token
 * @property string $secret
 * @property Request $request
 * @property Response $response
 */
class MaxBase extends \yii\base\Component
{
    public string $access_token;
    public string $secret;
    protected string $base = "https://platform-api.max.ru/";
    protected Client $client;
    public Request $request;
    public Response $response;

    /**
     * @return void
     */
    public function init()
    {
        $this->client = new Client();
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data) {
            \Yii::error($data);
            $this->request = new Request($data);
        }
    }

    /**
     * @param string $method
     * @param string $action
     * @param array $data
     * @param array|null $args
     * @return \garmayev\max\types\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(string $method, string $action, array $data, ?array $args = null)
    {
        if ($args && is_array($args)) {
            $result = $this->client->request($method, $this->base . $action . '?' . http_build_query($args), [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => $this->access_token,
                ],
                'body' => json_encode($data)
            ]);
        } else {
            $result = $this->client->request($method, $this->base . $action, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => $this->access_token,
                ],
                'body' => json_encode($data)
            ]);
        }
        \Yii::error( json_decode($result->getBody()->getContents(), true));
        return new Response(json_decode($result->getBody()->getContents(), true));
    }
}