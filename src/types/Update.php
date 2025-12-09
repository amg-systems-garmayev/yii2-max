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
}