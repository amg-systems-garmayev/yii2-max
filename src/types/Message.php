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
    private string $_mid;
    private int $_seq;
    private string $_text;
    private array $_attachments;
    private array $_markup;
    private Recipient $_recipient;
    private int $_timestamp;
    private MessageBody $_body;
    private User $_sender;

    /**
     * @return string
     */
    public function getMid(): string
    {
        return $this->_mid;
    }

    /**
     * @param string $mid
     * @return void
     */
    public function setMid(string $mid): void
    {
        $this->_mid = $mid;
    }

    /**
     * @return int
     */
    public function getSeq(): int
    {
        return $this->_seq;
    }

    /**
     * @param int $seq
     * @return void
     */
    public function setSeq(int $seq): void
    {
        $this->_seq = $seq;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->_text;
    }

    /**
     * @param string $text
     * @return void
     */
    public function setText(string $text): void
    {
        $this->_text = $text;
    }

    /**
     * @return array
     */
    public function getAttachments(): array
    {
        return $this->_attachments;
    }

    /**
     * @param array $attachments
     * @return void
     */
    public function setAttachments(array $attachments): void
    {
        foreach ($attachments as $attachment) {
            $this->_attachments[] = new Attachment($attachments);
        }
    }

    /**
     * @return array
     */
    public function getMarkup(): array
    {
        return $this->_markup;
    }

    /**
     * @param array $markup
     * @return void
     */
    public function setMarkup(array $markup): void
    {
        foreach ($markup as $k => $v) {
            $this->_markup[] = new Markup($markup);
        }
    }
  
    public function getRecipient() {
        return $this->_recipient;
    }
  
    public function setRecipient($value):void
    {
        $this->_recipient = new Recipient($value);
    }

    public function getTimestamp()
    {
        return $this->_timestamp;
    }

    public function setTimestamp(int $value): void
    {
        $this->_timestamp = $value;
    }

    public function getBody()
    {
        return $this->_body;
    }

    public function setBody($value): void
    {
        $this->_body = new MessageBody($value);
    }

    public function getSender()
    {
        return $this->_sender;
    }

    public function setSender($value)
    {
        $this->_sender = new User($value);
    }
}