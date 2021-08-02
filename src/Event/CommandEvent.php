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
    public function __construct(
        private Message $message,
        private string $command,
        private ?string $argument = null
    ) {
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function getArgument(): ?string
    {
        return $this->argument;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }
}
