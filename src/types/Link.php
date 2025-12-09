<?php

namespace garmayev\max\types;

use yii\base\Model;

/**
 * @property string $type
 * @property User $sender
 * @property int $chat_id
 * @property Message $message
 */
class Link extends Model
{
    public string $_type;
    public User $_sender;
    public int $_chat_id;
    public Message $_message;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->_type;
    }

    /**
     * @param string $type
     * @return void
     */
    public function setType(string $type): void
    {
        $this->_type = $type;
    }

    /**
     * @return User
     */
    public function getSender(): User
    {
        return $this->_sender;
    }

    /**
     * @param User $sender
     * @return void
     */
    public function setSender(User $sender): void
    {
        $this->_sender = new User($sender);
    }

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
     * @return Message
     */
    public function getMessage(): Message
    {
        return $this->_message;
    }

    /**
     * @param Message $message
     * @return void
     */
    public function setMessage(Message $message): void
    {
        $this->_message = new Message($message);
    }
}