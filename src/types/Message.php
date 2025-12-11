<?php

namespace garmayev\max\types;

use yii\base\Model;

/**
 * @property string $mid
 * @property integer $seq
 * @property string $text
 * @property Attachment[] $attachments
 * @property Markup[] $markup
 * @property Recipient $recipient
 * @property int $timestamp
 * @property MessageBody $body
 * @property User $sender
 */
class Message extends Model
{
    private User $_sender;
    private Recipient $_recipient;
    private int $_timestamp;
    private Link $_link;
    private MessageBody $_body;
    private Stat $_stat;
    private $_url;

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
     * @return mixed
     */
    public function getLink()
    {
        return $this->_link;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link): void
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
     * @return mixed
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url): void
    {
        $this->_url = $url;
    }
}