<?php

declare(strict_types=1);

namespace App\Service;

use App\Event\CommandEvent;
use App\Event\ImplicitSubscriptionEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use TelegramBot\Api\Types\Update;

/**
 * Class PlayLoadService
 * @package App\Service
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class PlayLoadService
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    /**
     * Process Telegram webhook event and dispatch internal event
     */
    public function negotiate(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $update = Update::fromResponse($data);
        $message = $update->getMessage();

        if ($message) {
            if (!empty($message->getEntities())) {
                foreach ($message->getEntities() as $entity) {
                    if ($entity->getType() === 'bot_command') {
                        $command = trim(substr($message->getText(), $entity->getOffset(), $entity->getLength()));
                        $argument = trim(str_replace($command, "", $message->getText()));
                        $this->dispatcher->dispatch(new CommandEvent($message, $command, $argument));
                    }
                }
            } else {
                $this->dispatcher->dispatch(new ImplicitSubscriptionEvent($message, $message->getText()));
                $this->dispatcher->dispatch(new CommandEvent($message, '/horaire', $message->getText()));
            }
        }
    }
}
