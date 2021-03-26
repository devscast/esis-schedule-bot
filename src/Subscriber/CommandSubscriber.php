<?php

declare(strict_types=1);

namespace App\Subscriber;

use App\Event\CommandEvent;
use App\Service\Timetable\Exception\EmptyPromotionException;
use App\Service\Timetable\Exception\InvalidPromotionException;
use App\Service\Timetable\Exception\UnavailableTimetableException;
use App\Service\Timetable\TimetableService;
use CURLFile;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\InvalidArgumentException;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use TelegramBot\Api\Types\ReplyKeyboardRemove;

/**
 * Class CommandSubscriber
 * @package App\Subscriber
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class CommandSubscriber implements EventSubscriberInterface
{
    private TimetableService $timetable;
    private BotApi $api;
    private LoggerInterface $logger;

    /**
     * CommandSubscriber constructor.
     * @param TimetableService $timetable
     * @param LoggerInterface $logger
     * @param BotApi $api
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(TimetableService $timetable, LoggerInterface $logger, BotApi $api)
    {
        $this->timetable = $timetable;
        $this->api = $api;
        $this->logger = $logger;
    }

    /**
     * @return string[]
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public static function getSubscribedEvents()
    {
        return [
            CommandEvent::class => 'onCommand'
        ];
    }

    /**
     * @param CommandEvent $event
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onCommand(CommandEvent $event)
    {
        try {
            switch ($event->getCommand()) {
                case '/horaire@EsisHoraireBot':
                case '/horaire':
                    try {
                        $file = $this->timetable->getTimetableDocument($event->getArgument());
                        $document = new CURLFile($file, 'application/pdf');
                        $this->api->sendDocument($event->getChatId(), $document, "Voici l'horaire demandé", $event->getMessageId());
                    } catch (InvalidPromotionException | EmptyPromotionException | UnavailableTimetableException $e) {
                        $this->api->sendMessage($event->getChatId(), $e->getMessage());
                        $this->logger->error($e->getMessage(), $e->getTrace());
                    }
                    break;

                case '/start@EsisHoraireBot':
                case '/start':
                    $keyboard = new ReplyKeyboardMarkup(TimetableService::KEYBOARD_MAKEUP, false);
                    $this->api->sendMessage($event->getChatId(), "Horaire Esis Salama Disponible", null, false, null, $keyboard);
                    break;

                case '/removeKeyboard@EsisHoraireBot':
                case '/removeKeyboard':
                    $keyboard = new ReplyKeyboardRemove();
                    $this->api->sendMessage($event->getChatId(), null, null, false, null, $keyboard);
                    break;

                default:
                    $command = $event->getCommand();
                    $this->api->sendMessage($event->getChatId(), "Commande Indisponible ${command}");
                    break;
            }
        } catch (InvalidArgumentException | Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }
}
