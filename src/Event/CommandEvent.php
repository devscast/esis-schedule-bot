<?php

declare(strict_types=1);

namespace App\Event;

/**
 * Class CommandEvent
 * @package App\Event
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class CommandEvent
{
    private string $command;
    private string $argument;
    private string $chatId;
    private string $messageId;

    /**
     * CommandEvent constructor.
     * @param string $command
     * @param string $argument
     * @param string $chatId
     * @param string $messageId
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(string $command, string $argument, string $chatId, string $messageId)
    {
        $this->command = $command;
        $this->argument = $argument;
        $this->chatId = $chatId;
        $this->messageId = $messageId;
    }

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getArgument(): string
    {
        return $this->argument;
    }

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getChatId(): string
    {
        return $this->chatId;
    }

    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getMessageId(): string
    {
        return $this->messageId;
    }
}
