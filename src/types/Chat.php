<?php

namespace garmayev\max\types;

use yii\base\Model;

/**
 * @property int $chat_id
 * @property string $type
 * @property string $status
 * @property string $title
 * @property Icon $icon
 */
class Chat extends Model
{
    const TYPE_CHAT = "chat";

    const STATUS_ACTIVE = "active";
    const STATUS_REMOVED = "removed";
    const STATUS_LEFT = "left";
    const STATUS_CLOSED = "closed";

    public int $_chat_id;
    public string $_type;
    public string $_status;
    public string $_title;
    public Icon $_icon;
    public int $_last_event_time;
    public int $_participants_count;
    public int $_owner_id;
    public array $_participants;
    public bool $_is_public;
    public string $_link;
    public string $_description;
    public User $_dialog_with_user;
    public string $_chat_message_id;
    public Message $_pinned_message;

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
     * @return string
     */
    public function getStatus(): string
    {
        return $this->_status;
    }

    /**
     * @param string $status
     * @return void
     */
    public function setStatus(string $status): void
    {
        $this->_status = $status;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->_title;
    }

    /**
     * @param string $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->_title = $title;
    }

    /**
     * @return Icon
     */
    public function getIcon(): Icon
    {
        return $this->_icon;
    }

    /**
     * @param Icon $icon
     * @return void
     */
    public function setIcon(Icon $icon): void
    {
        $this->_icon = new Icon($icon);
    }

    /**
     * @return int
     */
    public function getLast_event_time(): int
    {
        return $this->_last_event_time;
    }

    /**
     * @param int $value
     * @return void
     */
    public function setLast_event_time(int $value): void
    {
        $this->_last_event_time = $value;
    }

    /**
     * @return int
     */
    public function getParticipants_count(): int
    {
        return $this->_participants_count;
    }

    /**
     * @param int $value
     * @return void
     */
    public function setParticipants_count(int $value): void
    {
        $this->_participants_count = $value;
    }

    /**
     * @return int
     */
    public function getOwner_id(): int
    {
        return $this->_owner_id;
    }

    /**
     * @param int $owner_id
     * @return void
     */
    public function setOwner_id(int $owner_id): void
    {
        $this->_owner_id = $owner_id;
    }

    /**
     * @return array
     */
    public function getParticipants(): array
    {
        return $this->_participants;
    }

    /**
     * @param array $participants
     * @return void
     */
    public function setParticipants(array $participants): void
    {
        $this->_participants = $participants;
    }

    /**
     * @return bool
     */
    public function isIs_public(): bool
    {
        return $this->_is_public;
    }

    /**
     * @param bool $is_public
     * @return void
     */
    public function setIs_public(bool $is_public): void
    {
        $this->_is_public = $is_public;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->_link;
    }

    /**
     * @param string $link
     * @return void
     */
    public function setLink(string $link): void
    {
        $this->_link = $link;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->_description;
    }

    /**
     * @param string $description
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->_description = $description;
    }

    /**
     * @return User
     */
    public function getDialogWithUser(): User
    {
        return $this->_dialog_with_user;
    }

    /**
     * @param User $dialog_with_user
     * @return void
     */
    public function setDialogWithUser(User $dialog_with_user): void
    {
        $this->_dialog_with_user = new User($dialog_with_user);
    }

    /**
     * @return string
     */
    public function getChat_message_id(): string
    {
        return $this->_chat_message_id;
    }

    /**
     * @param string $chat_message_id
     * @return void
     */
    public function setChat_message_id(string $chat_message_id): void
    {
        $this->_chat_message_id = $chat_message_id;
    }

    /**
     * @return Message
     */
    public function getPinned_message(): Message
    {
        return $this->_pinned_message;
    }

    /**
     * @param Message $pinned_message
     * @return void
     */
    public function setPinned_message(Message $pinned_message): void
    {
        $this->_pinned_message = new Message($pinned_message);
    }
}