<?php

namespace garmayev\max\base;

use garmayev\max\types\Update;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

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
     * Отправка запроса к API MAX
     *
     * @param string $method HTTP метод (GET, POST, PUT, DELETE)
     * @param string $action API endpoint (messages, answers и т.д.)
     * @param array $data Тело запроса
     * @param array|null $args Query параметры (user_id, message_id, callback_id и т.д.)
     * @return \garmayev\max\types\Response
     * @throws GuzzleException
     */
    public function send(string $method, string $action, array $data = [], ?array $args = null)
    {
        $url = $this->base . $action;

        // Добавляем query параметры если они есть
        if ($args && is_array($args) && !empty($args)) {
            $url .= '?' . http_build_query($args);
        }

        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => $this->access_token,
            ],
        ];

        // Для GET запросов данные передаем как query параметры
        if (strtoupper($method) === 'GET' && !empty($data)) {
            if (isset($options['query'])) {
                $options['query'] = array_merge($options['query'], $data);
            } else {
                $options['query'] = $data;
            }
        }
        // Для DELETE запросов не отправляем тело, если нет специальных данных
        else if (strtoupper($method) === 'DELETE' && empty($data)) {
            // Не добавляем body для простых DELETE запросов
        }
        // Для POST, PUT и DELETE с данными добавляем тело запроса
        else if (!empty($data)) {
            $options['json'] = $data;
        }

        try {
            $response = $this->client->request($method, $url, $options);
            $responseBody = $response->getBody()->getContents();

            // Логируем ответ
            \Yii::error(['response' => json_decode($responseBody, true)]);

            return new \garmayev\max\types\Response(json_decode($responseBody, true));

        } catch (GuzzleException $e) {
            \Yii::error([
                'error' => $e->getMessage(),
                'url' => $url,
                'method' => $method,
                'options' => $options
            ]);
            throw $e;
        }
    }
}