<?php

namespace garmayev\max\types;

use yii\base\Model;

class Update extends Model
{
    const TYPE_MESSAGE_CREATED = 'message_created';
    const TYPE_MESSAGE_CALLBACK = 'message_callback';
    const TYPE_MESSAGE_EDITED = 'message_edited';
    const TYPE_MESSAGE_REMOVED = 'message_removed';
    const TYPE_BOT_ADDED = 'bot_added';
    const TYPE_BOT_REMOVED = 'bot_removed';
    const TYPE_DIALOG_MUTED = 'dialog_mutated';
    const TYPE_DIALOG_UNMUTED = 'dialog_unmuted';
    const TYPE_DIALOG_CLEARED = 'dialog_cleared';
    const TYPE_DIALOG_REMOVED = 'dialog_removed';
    const TYPE_USER_ADDED = 'user_added';
    const TYPE_USER_REMOVED = 'user_removed';
    const TYPE_BOT_STARTED = 'bot_started';
    const TYPE_BOT_STOPPED = 'bot_stopped';
    const TYPE_CHAT_TITLE_CHANGED = 'chat_title_changed';
    const TYPE_MESSAGE_CHAT_CREATED = 'message_chat_created';

    private string $_update_type;
    private int $_timestamp;
    private Message $_message;
    private string $_user_locale;

    /**
     * @return string
     */
    public function getUpdate_type()
    {
        return $this->_update_type;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setUpdate_type(string $value)
    {
        $this->_update_type = $value;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->_timestamp;
    }

    /**
     * @param int $value
     * @return void
     */
    public function setTimestamp(int $value)
    {
        $this->_timestamp = $value;
    }

    /**
     * @return Message
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * @param array $value
     * @return void
     */
    public function setMessage(array $value)
    {
        $this->_message = new Message($value);
    }

    /**
     * @return string
     */
    public function getUser_locale()
    {
        return $this->_user_locale;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setUser_locale(string $value)
    {
        $this->_user_locale = $value;
    }
}