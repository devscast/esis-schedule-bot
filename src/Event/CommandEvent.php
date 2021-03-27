<?php

declare(strict_types=1);

namespace App\Event;

use TelegramBot\Api\Types\Message;

/**
 * Class CommandEvent
 * @package App\Event
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class CommandEvent
{
    private string $command;
    private ?string $argument;
    private Message $message;

    /**
     * CommandEvent constructor
     * @param Message $message .
     * @param string $command
     * @param ?string $argument
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(Message $message, string $command, ?string $argument = null)
    {
        $this->message = $message;
        $this->command = $command;
        $this->argument = $argument;
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
     * @return null|string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getArgument(): ?string
    {
        return $this->argument;
    }

    /**
     * @return Message
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getMessage(): Message
    {
        return $this->message;
    }
}
