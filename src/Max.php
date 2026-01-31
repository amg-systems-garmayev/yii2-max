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

    private EventHandler $handler;

    /**
     * @param array $config Конфигурация бота
     */
    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->handler = new EventHandler($this);
    }

    /**
     * Инициализирует обработчик событий
     *
     * @return EventHandler
     */
    public function handler(): EventHandler
    {
        return $this->handler->init();
    }

    /**
     * Установка вебхука
     *
     * @param string $url URL вебхука
     * @param array $types Типы событий для подписки
     * @return \garmayev\max\types\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function setWebhook(string $url, array $types)
    {
        return parent::send('POST', 'subscriptions', [
            'url' => $url,
            'update_types' => $types,
            'secret' => $this->secret,
        ]);
    }

    /**
     * Удаление вебхука
     *
     * @return \garmayev\max\types\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteWebhook()
    {
        return parent::send('DELETE', 'subscriptions');
    }

    /**
     * Отправка сообщения
     *
     * @param array $params Параметры сообщения
     * @param array $args Query параметры (user_id, chat_id, chat_type)
     * @return \garmayev\max\types\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendMessage(array $params, array $args = [])
    {
        return parent::send('POST', 'messages', $params, $args);
    }

    /**
     * Редактирование сообщения
     *
     * @param array $params Новые параметры сообщения
     * @param array $args Query параметры (message_id - обязательно)
     * @return \garmayev\max\types\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function editMessage(array $params, array $args = [])
    {
        return parent::send('PUT', 'messages', $params, $args);
    }

    /**
     * Удаление сообщения
     *
     * @param array $args Query параметры (message_id - обязательно)
     * @return \garmayev\max\types\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteMessage(array $args = [])
    {
        return parent::send('DELETE', 'messages', [], $args);
    }

    /**
     * Отправка ответа на callback
     *
     * @param array $params Параметры сообщения
     * @param array $args Query параметры (callback_id - обязательно)
     * @return \garmayev\max\types\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendAnswer(array $params, array $args = [])
    {
        return parent::send('POST', 'answers', $params, $args);
    }

    /**
     * Получение информации о пользователе
     *
     * @param array $args Query параметры (user_id - обязательно)
     * @return \garmayev\max\types\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUserInfo(array $args = [])
    {
        return parent::send('GET', 'users', [], $args);
    }

    /**
     * Получение информации о чате
     *
     * @param array $args Query параметры (chat_id - обязательно)
     * @return \garmayev\max\types\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getChatInfo(array $args = [])
    {
        return parent::send('GET', 'chats', [], $args);
    }

    /**
     * Отправка уведомления о выполнении действия
     *
     * @param array $args Query параметры (callback_id - обязательно)
     * @return \garmayev\max\types\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendCallbackAnswer(array $args = [])
    {
        return parent::send('POST', 'answers', [
            'message' => [
                'text' => '✅ Действие выполнено'
            ]
        ], $args);
    }
}