<?php
namespace garmayev\max\types;

class MessageBody extends \yii\base\Model
{
    private string $_mid;
    private int $_seq;
    private string $_text;
    private array $_attachments;

    public function getMid()
    {
        return $this->_mid;
    }

    public function setMid($value)
    {
        $this->_mid = $value;
    }

    public function getSeq()
    {
        return $this->_seq;
    }

    public function setSeq($value)
    {
        $this->_seq = $value;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($value)
    {
        $this->text = $value;
    }

    public function getAttachments()
    {
        return $this->_attachments;
    }

    public function setAttachments($value)
    {
        foreach ($value as $item)
        {
            $this->_attachments[] = new Attachment($item);
        }
    }
}