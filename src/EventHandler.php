<?php

namespace garmayev\max;

use garmayev\max\base\Request;
use garmayev\max\types\Update;
use garmayev\max\types\Callback;
use garmayev\max\types\Message;
use garmayev\max\types\Chat;
use garmayev\max\types\User;
use garmayev\max\base\MaxBase;

/**
 * Обработчик событий MAX API
 */
class EventHandler
{
    private array $handlers = [];
    private MaxBase $bot;
    private ?Request $request = null;

    /**
     * @param MaxBase $bot Экземпляр бота
     */
    public function __construct(MaxBase $bot)
    {
        $this->bot = $bot;
    }

    /**
     * Инициализирует обработчик, парсит входящий запрос
     *
     * @return $this
     */
    public function init(): self
    {
        // Если запрос уже инициализирован в MaxBase, используем его
        if (isset($this->bot->request)) {
            $this->request = $this->bot->request;
        } else {
            // Иначе парсим входящие данные
            $data = json_decode(file_get_contents('php://input'), true);
            if ($data) {
                $this->request = new Request($data);
            }
        }

        return $this;
    }

    /**
     * Регистрирует обработчик для определенного типа события
     *
     * @param string $eventType Тип события
     * @param callable $handler Функция-обработчик
     * @return $this
     */
    public function on(string $eventType, callable $handler): self
    {
        $this->handlers[$eventType][] = $handler;
        return $this;
    }

    /**
     * Регистрирует обработчик для текстовых сообщений
     *
     * @param callable $handler Функция-обработчик
     * @return $this
     */
    public function onMessage(callable $handler): self
    {
        return $this->on(Update::TYPE_MESSAGE_CREATED, $handler);
    }

    /**
     * Регистрирует обработчик для нажатия кнопок (callback)
     *
     * @param callable $handler Функция-обработчик
     * @return $this
     */
    public function onCallback(callable $handler): self
    {
        return $this->on(Update::TYPE_MESSAGE_CALLBACK, $handler);
    }

    /**
     * Регистрирует обработчик для редактирования сообщений
     *
     * @param callable $handler Функция-обработчик
     * @return $this
     */
    public function onMessageEdited(callable $handler): self
    {
        return $this->on(Update::TYPE_MESSAGE_EDITED, $handler);
    }

    /**
     * Регистрирует обработчик для удаления сообщений
     *
     * @param callable $handler Функция-обработчик
     * @return $this
     */
    public function onMessageRemoved(callable $handler): self
    {
        return $this->on(Update::TYPE_MESSAGE_REMOVED, $handler);
    }

    /**
     * Регистрирует обработчик для добавления бота в чат
     *
     * @param callable $handler Функция-обработчик
     * @return $this
     */
    public function onBotAdded(callable $handler): self
    {
        return $this->on(Update::TYPE_BOT_ADDED, $handler);
    }

    /**
     * Регистрирует обработчик для удаления бота из чата
     *
     * @param callable $handler Функция-обработчик
     * @return $this
     */
    public function onBotRemoved(callable $handler): self
    {
        return $this->on(Update::TYPE_BOT_REMOVED, $handler);
    }

    /**
     * Регистрирует обработчик для старта бота (начало диалога)
     *
     * @param callable $handler Функция-обработчик
     * @return $this
     */
    public function onBotStarted(callable $handler): self
    {
        return $this->on(Update::TYPE_BOT_STARTED, $handler);
    }

    /**
     * Регистрирует обработчик для остановки бота
     *
     * @param callable $handler Функция-обработчик
     * @return $this
     */
    public function onBotStopped(callable $handler): self
    {
        return $this->on(Update::TYPE_BOT_STOPPED, $handler);
    }

    /**
     * Регистрирует обработчик для добавления пользователя в чат
     *
     * @param callable $handler Функция-обработчик
     * @return $this
     */
    public function onUserAdded(callable $handler): self
    {
        return $this->on(Update::TYPE_USER_ADDED, $handler);
    }

    /**
     * Регистрирует обработчик для удаления пользователя из чата
     *
     * @param callable $handler Функция-обработчик
     * @return $this
     */
    public function onUserRemoved(callable $handler): self
    {
        return $this->on(Update::TYPE_USER_REMOVED, $handler);
    }

    /**
     * Регистрирует обработчик для изменения названия чата
     *
     * @param callable $handler Функция-обработчик
     * @return $this
     */
    public function onChatTitleChanged(callable $handler): self
    {
        return $this->on(Update::TYPE_CHAT_TITLE_CHANGED, $handler);
    }

    /**
     * Регистрирует обработчик для создания чата
     *
     * @param callable $handler Функция-обработчик
     * @return $this
     */
    public function onChatCreated(callable $handler): self
    {
        return $this->on(Update::TYPE_MESSAGE_CHAT_CREATED, $handler);
    }

    /**
     * Регистрирует обработчик для текстовых команд
     *
     * @param string $command Команда (без символа /)
     * @param callable $handler Функция-обработчик
     * @return $this
     */
    public function command(string $command, callable $handler): self
    {
        $this->on(Update::TYPE_MESSAGE_CREATED, function (Message $message) use ($command, $handler) {
            $text = $message->body->text ?? '';

            // Проверяем, начинается ли текст с команды
            if (strpos($text, "/$command") === 0 || strpos($text, "!$command") === 0) {
                $args = $this->parseCommandArgs($text);
                $handler($message, $args);
                return true; // Останавливаем дальнейшую обработку
            }

            return false;
        });

        return $this;
    }

    /**
     * Регистрирует обработчик для callback с определенным payload
     *
     * @param string $payload Значение payload
     * @param callable $handler Функция-обработчик
     * @return $this
     */
    public function callback(string $payload, callable $handler): self
    {
        $this->on(Update::TYPE_MESSAGE_CALLBACK, function (Request $request) use ($payload, $handler) {
            if ($request->callback->getPayload() === $payload) {
                $handler($request->callback);
                return true; // Останавливаем дальнейшую обработку
            }

            return false;
        });

        return $this;
    }

    /**
     * Регистрирует обработчик для текста, содержащего определенную подстроку
     *
     * @param string $text Текст для поиска
     * @param callable $handler Функция-обработчик
     * @return $this
     */
    public function contains(string $text, callable $handler): self
    {
        $this->on(Update::TYPE_MESSAGE_CREATED, function (Message $message) use ($text, $handler) {
            $messageText = $message->body->text ?? '';

            if (stripos($messageText, $text) !== false) {
                $handler($message);
                return true;
            }

            return false;
        });

        return $this;
    }

    /**
     * Регистрирует обработчик для текста, соответствующего регулярному выражению
     *
     * @param string $pattern Регулярное выражение
     * @param callable $handler Функция-обработчик
     * @return $this
     */
    public function regex(string $pattern, callable $handler): self
    {
        $this->on(Update::TYPE_MESSAGE_CREATED, function (Message $message) use ($pattern, $handler) {
            $messageText = $message->body->text ?? '';

            if (preg_match($pattern, $messageText, $matches)) {
                $handler($message, $matches);
                return true;
            }

            return false;
        });

        return $this;
    }

    /**
     * Регистрирует обработчик по умолчанию (если не сработали другие обработчики)
     *
     * @param callable $handler Функция-обработчик
     * @return $this
     */
    public function default(callable $handler): self
    {
        $this->handlers['default'][] = $handler;
        return $this;
    }

    /**
     * Обрабатывает входящее событие
     *
     * @return bool Успешно ли обработано событие
     */
    public function handle(): bool
    {
        if (!$this->request) {
            return false;
        }

        $updateType = $this->request->getUpdate_type();
        $eventHandled = false;

        // Вызываем обработчики для конкретного типа события
        if (isset($this->handlers[$updateType])) {
            foreach ($this->handlers[$updateType] as $handler) {
                $result = $handler($this->request);
                if ($result === true) {
                    $eventHandled = true;
                    break; // Прекращаем выполнение других обработчиков
                }
            }
        }

        // Если событие не обработано, вызываем обработчики по умолчанию
        if (!$eventHandled && isset($this->handlers['default'])) {
            foreach ($this->handlers['default'] as $handler) {
                $handler($this->request);
            }
        }

        return $eventHandled;
    }

    /**
     * Возвращает текущий запрос
     *
     * @return Request|null
     */
    public function getRequest(): ?Request
    {
        return $this->request;
    }

    /**
     * Возвращает сообщение из запроса (если есть)
     *
     * @return Message|null
     */
    public function getMessage(): ?Message
    {
        if ($this->request) {
            return $this->request->getMessage();
        }

        return null;
    }

    /**
     * Возвращает callback из запроса (если есть)
     *
     * @return Callback|null
     */
    public function getCallback(): ?Callback
    {
        if ($this->request) {
            return $this->request->getCallback();
        }

        return null;
    }

    /**
     * Возвращает чат из запроса (если есть)
     *
     * @return Chat|null
     */
    public function getChat(): ?Chat
    {
        $message = $this->getMessage();
        if ($message !== null && $message->getRecipient() !== null) {
            // Создаем простой объект Chat из recipient
            $recipient = $message->getRecipient();
            $chat = new Chat();
            $chat->setChat_id($recipient->getChat_id());
            $chat->setType($recipient->getChat_type());
            return $chat;
        }

        return null;
    }

    /**
     * Возвращает пользователя из запроса (если есть)
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        // Пробуем получить пользователя из разных источников
        if ($this->request) {
            // Сначала из свойства user
            if ($this->request->getUser() !== null) {
                return $this->request->getUser();
            }

            // Затем из сообщения
            if ($this->request->getMessage() !== null) {
                $message = $this->request->getMessage();
                if ($message->getSender() !== null) {
                    return $message->getSender();
                }
            }

            // Затем из callback
            if ($this->request->getCallback() !== null) {
                $callback = $this->request->getCallback();
                if ($callback->getUser() !== null) {
                    return $callback->getUser();
                }
            }

            // Пробуем создать пользователя из user_id
            if ($this->request->getUser_id() !== null) {
                return new User([
                    'user_id' => $this->request->getUser_id(),
                    'first_name' => 'Пользователь',
                    'last_name' => '',
                    'name' => 'Пользователь'
                ]);
            }
        }

        return null;
    }

    /**
     * Парсит аргументы команды
     *
     * @param string $text Текст сообщения
     * @return array Аргументы команды
     */
    private function parseCommandArgs(string $text): array
    {
        $parts = preg_split('/\s+/', trim($text));
        array_shift($parts); // Убираем саму команду

        return $parts;
    }
}