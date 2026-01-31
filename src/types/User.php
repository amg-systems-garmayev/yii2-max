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
    public ?int $_user_id = null;
    public ?string $_first_name = null;
    public ?string $_last_name = null;
    public ?string $_name = null;
    public ?string $_username = null;
    public ?bool $_is_bot = null;
    public ?int $_last_activity_time = null;
    public ?string $_description = null;
    public ?string $_avatar_url = null;
    public ?string $_full_avatar_url = null;

    /**
     * @param array $data
     */
    public function __construct($data, $config = [])
    {
        if (is_array($data)) {
            // Если это массив, устанавливаем свойства
            foreach ($data as $key => $value) {
                $property = '_' . $key;
                if (property_exists($this, $property)) {
                    $this->$property = $value;
                }
            }
        } elseif ($data instanceof User) {
            // Если это объект User, копируем свойства
            foreach (get_object_vars($data) as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }

        parent::__construct($config);
    }

    /**
     * @return int|null
     */
    public function getUser_id(): ?int
    {
        return $this->_user_id;
    }

    /**
     * @param int|null $user_id
     * @return void
     */
    public function setUser_id(?int $user_id): void
    {
        $this->_user_id = $user_id;
    }

    /**
     * @return string|null
     */
    public function getFirst_name(): ?string
    {
        return $this->_first_name;
    }

    /**
     * @param string|null $first_name
     * @return void
     */
    public function setFirst_name(?string $first_name): void
    {
        $this->_first_name = $first_name;
    }

    /**
     * @return string|null
     */
    public function getLast_name(): ?string
    {
        return $this->_last_name;
    }

    /**
     * @param string|null $last_name
     * @return void
     */
    public function setLast_name(?string $last_name): void
    {
        $this->_last_name = $last_name;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->_name;
    }

    /**
     * @param string|null $name
     * @return void
     */
    public function setName(?string $name): void
    {
        $this->_name = $name;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->_username;
    }

    /**
     * @param string|null $username
     * @return void
     */
    public function setUsername(?string $username): void
    {
        $this->_username = $username;
    }

    /**
     * @return bool|null
     */
    public function isIs_bot(): ?bool
    {
        return $this->_is_bot;
    }

    /**
     * @param bool|null $is_bot
     * @return void
     */
    public function setIs_bot(?bool $is_bot): void
    {
        $this->_is_bot = $is_bot;
    }

    /**
     * @return int|null
     */
    public function getLast_activity_time(): ?int
    {
        return $this->_last_activity_time;
    }

    /**
     * @param int|null $last_activity_time
     * @return void
     */
    public function setLast_activity_time(?int $last_activity_time): void
    {
        $this->_last_activity_time = $last_activity_time;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->_description;
    }

    /**
     * @param string|null $description
     * @return void
     */
    public function setDescription(?string $description): void
    {
        $this->_description = $description;
    }

    /**
     * @return string|null
     */
    public function getAvatar_url(): ?string
    {
        return $this->_avatar_url;
    }

    /**
     * @param string|null $avatar_url
     * @return void
     */
    public function setAvatar_url(?string $avatar_url): void
    {
        $this->_avatar_url = $avatar_url;
    }

    /**
     * @return string|null
     */
    public function getFull_avatar_url(): ?string
    {
        return $this->_full_avatar_url;
    }

    /**
     * @param string|null $full_avatar_url
     * @return void
     */
    public function setFull_avatar_url(?string $full_avatar_url): void
    {
        $this->_full_avatar_url = $full_avatar_url;
    }

    /**
     * Возвращает отображаемое имя пользователя
     *
     * @return string
     */
    public function getDisplayName(): string
    {
        if ($this->_first_name && $this->_last_name) {
            return $this->_first_name . ' ' . $this->_last_name;
        }

        if ($this->_first_name) {
            return $this->_first_name;
        }

        if ($this->_username) {
            return '@' . $this->_username;
        }

        if ($this->_name) {
            return $this->_name;
        }

        return 'Пользователь';
    }
}