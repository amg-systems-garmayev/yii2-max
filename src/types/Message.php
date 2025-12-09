<?php

namespace garmayev\max\types;

use yii\base\Model;

/**
 * @property string $mid
 * @property integer $seq
 * @property string $text
 * @property Attachment[] $attachments
 * @property Markup[] $markup
 */
class Message extends Model
{
    public string $_mid;
    public int $_seq;
    public string $_text;
    public array $_attachments;
    public array $_markup;

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
}