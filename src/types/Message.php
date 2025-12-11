<?php

namespace garmayev\max\types;

use yii\base\Model;

/**
 * @property User $sender
 * @property Recipient $recipient
 * @property int $timestamp
 * @property Link $link
 * @property MessageBody $body
 * @property Stat $stat
 * @property string $url
 */
class Message extends Model
{
    private User $_sender;
    private Recipient $_recipient;
    private int $_timestamp;
    private Link $_link;
    private MessageBody $_body;
    private Stat $_stat;
    private string $_url;

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
        $this->_sender = $sender;
    }

    /**
     * @return Recipient
     */
    public function getRecipient(): Recipient
    {
        return $this->_recipient;
    }

    /**
     * @param Recipient $recipient
     * @return void
     */
    public function setRecipient(Recipient $recipient): void
    {
        $this->_recipient = $recipient;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->_timestamp;
    }

    /**
     * @param int $timestamp
     * @return void
     */
    public function setTimestamp(int $timestamp): void
    {
        $this->_timestamp = $timestamp;
    }

    /**
     * @return Link
     */
    public function getLink(): Link
    {
        return $this->_link;
    }

    /**
     * @param Link $link
     * @return void
     */
    public function setLink(Link $link): void
    {
        $this->_link = $link;
    }

    /**
     * @return MessageBody
     */
    public function getBody(): MessageBody
    {
        return $this->_body;
    }

    /**
     * @param MessageBody $body
     * @return void
     */
    public function setBody(MessageBody $body): void
    {
        $this->_body = $body;
    }

    /**
     * @return Stat
     */
    public function getStat(): Stat
    {
        return $this->_stat;
    }

    /**
     * @param Stat $stat
     * @return void
     */
    public function setStat(Stat $stat): void
    {
        $this->_stat = $stat;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->_url;
    }

    /**
     * @param string $url
     * @return void
     */
    public function setUrl(string $url): void
    {
        $this->_url = $url;
    }
}