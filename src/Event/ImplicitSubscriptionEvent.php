<?php

declare(strict_types=1);

namespace App\Event;

use TelegramBot\Api\Types\Message;

/**
 * Class SubscriptionEvent
 * @package App\Event
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class ImplicitSubscriptionEvent
{
    private ?string $argument;
    private Message $message;

    /**
     * CommandEvent constructor
     * @param Message $message .
     * @param ?string $argument
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(Message $message, ?string $argument = null)
    {
        $this->message = $message;
        $this->argument = $argument;
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
