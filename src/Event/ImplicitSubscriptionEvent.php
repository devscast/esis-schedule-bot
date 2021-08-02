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
    public function __construct(
        private Message $message,
        private ?string $argument = null
    ) {
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
