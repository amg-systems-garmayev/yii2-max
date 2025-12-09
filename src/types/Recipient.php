<?php

namespace garmayev\max\types;

use yii\base\Model;

/**
 * @property int $chat_id
 * @property string $chat_type
 * @property int $user_id
 */
class Recipient extends Model
{
    public int $_chat_id;
    public string $_chat_type;
    public int $_user_id;

    /**
     * @return int
     */
    public function getChat_id(): int
    {
        return $this->_chat_id;
    }

    /**
     * @param int $chat_id
     * @return void
     */
    public function setChat_id(int $chat_id): void
    {
        $this->_chat_id = $chat_id;
    }

    /**
     * @return string
     */
    public function getChat_type(): string
    {
        return $this->_chat_type;
    }

    /**
     * @param string $chat_type
     * @return void
     */
    public function setChat_type(string $chat_type): void
    {
        $this->_chat_type = $chat_type;
    }

    /**
     * @return int
     */
    public function getUser_id(): int
    {
        return $this->_user_id;
    }

    /**
     * @param int $user_id
     * @return void
     */
    public function setUser_id(int $user_id): void
    {
        $this->_user_id = $user_id;
    }
}