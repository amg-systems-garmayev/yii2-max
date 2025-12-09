<?php

namespace garmayev\max\types;

use yii\base\Model;

/**
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $name
 * @property string $username
 * @property bool $is_bot
 * @property int $last_activity_time
 * @property string $description
 * @property string $avatar_url
 * @property string $full_avatar_url
 */
class User extends Model
{
    public int $_user_id;
    public string $_first_name;
    public string $_last_name;
    public string $_name;
    public string $_username;
    public bool $_is_bot;
    public int $_last_activity_time;
    public string $_description;
    public string $_avatar_url;
    public string $_full_avatar_url;

    /**
     * @return int
     */
    public function getUser_id(): int
    {
        return $this->_user_id;
    }

    /**
     * @param int $user_id
     * @return void
     */
    public function setUser_id(int $user_id): void
    {
        $this->_user_id = $user_id;
    }

    /**
     * @return string
     */
    public function getFirst_name(): string
    {
        return $this->_first_name;
    }

    /**
     * @param string $first_name
     * @return void
     */
    public function setFirst_name(string $first_name): void
    {
        $this->_first_name = $first_name;
    }

    /**
     * @return string
     */
    public function getLast_name(): string
    {
        return $this->_last_name;
    }

    /**
     * @param string $last_name
     * @return void
     */
    public function setLast_name(string $last_name): void
    {
        $this->_last_name = $last_name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->_name = $name;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->_username;
    }

    /**
     * @param string $username
     * @return void
     */
    public function setUsername(string $username): void
    {
        $this->_username = $username;
    }

    /**
     * @return bool
     */
    public function isIs_bot(): bool
    {
        return $this->_is_bot;
    }

    /**
     * @param bool $is_bot
     * @return void
     */
    public function setIs_bot(bool $is_bot): void
    {
        $this->_is_bot = $is_bot;
    }

    /**
     * @return int
     */
    public function getLast_activity_time(): int
    {
        return $this->_last_activity_time;
    }

    /**
     * @param int $last_activity_time
     * @return void
     */
    public function setLast_activity_time(int $last_activity_time): void
    {
        $this->_last_activity_time = $last_activity_time;
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
     * @return string
     */
    public function getAvatar_url(): string
    {
        return $this->_avatar_url;
    }

    /**
     * @param string $avatar_url
     * @return void
     */
    public function setAvatar_url(string $avatar_url): void
    {
        $this->_avatar_url = $avatar_url;
    }

    /**
     * @return string
     */
    public function getFull_avatar_url(): string
    {
        return $this->_full_avatar_url;
    }

    /**
     * @param string $full_avatar_url
     * @return void
     */
    public function setFull_avatar_url(string $full_avatar_url): void
    {
        $this->_full_avatar_url = $full_avatar_url;
    }
}