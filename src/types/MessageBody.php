<?php
namespace garmayev\max\types;

class MessageBody extends \yii\base\Model
{
    private string $_mid;
    private int $_seq;
    private string $_text;
    private array $_attachments;
    private array $_markup;

    /**
     * @return string
     */
    public function getMid()
    {
        return $this->_mid;
    }

    /**
     * @param $value
     * @return void
     */
    public function setMid($value)
    {
        $this->_mid = $value;
    }

    /**
     * @return int
     */
    public function getSeq()
    {
        return $this->_seq;
    }

    /**
     * @param $value
     * @return void
     */
    public function setSeq($value)
    {
        $this->_seq = $value;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->_text;
    }

    /**
     * @param $value
     * @return void
     */
    public function setText($value)
    {
        $this->_text = $value;
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->_attachments;
    }

    /**
     * @param $value
     * @return void
     */
    public function setAttachments($value)
    {
        foreach ($value as $item)
        {
//            \Yii::error($item);
            $this->_attachments[] = new Attachment($item);
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
        $this->_markup = $markup;
    }
}