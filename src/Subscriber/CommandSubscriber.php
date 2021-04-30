<?php

declare(strict_types=1);

namespace App\Subscriber;

use App\Event\CommandEvent;
use App\Service\Subscription\Exception\AlreadyHaveActiveSubscriptionException;
use App\Service\Subscription\Exception\EmptyPromotionException as SubscriptionEmptyPromotionException;
use App\Service\Subscription\Exception\NonActiveSubscriptionFoundException;
use App\Service\Subscription\SubscriptionService;
use App\Service\Timetable\Exception\EmptyPromotionException;
use App\Service\Timetable\Exception\InvalidPromotionException;
use App\Service\Timetable\Exception\UnavailableTimetableException;
use App\Service\Timetable\PromotionService;
use App\Service\Timetable\TimetableService;
use CURLFile;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\InvalidArgumentException;
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
    private SubscriptionService $subscription;

    /**
     * CommandSubscriber constructor.
     * @param TimetableService $timetable
     * @param SubscriptionService $subscription
     * @param LoggerInterface $logger
     * @param BotApi $api
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(
        TimetableService $timetable,
        SubscriptionService $subscription,
        LoggerInterface $logger,
        BotApi $api
    ) {
        $this->timetable = $timetable;
        $this->api = $api;
        $this->logger = $logger;
        $this->subscription = $subscription;
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
        $messageId = $event->getMessage()->getMessageId();
        $messageType = $event->getMessage()->getChat()->getType();

        // to avoid group supergroup or channel spam, the bot will reply directly in private
        $replyToMessageId = $messageType === "private" ? $messageId : null;
        $chatId = $messageType === "private" ?
            $event->getMessage()->getChat()->getId() :
            $event->getMessage()->getFrom()->getId();

        try {
            switch ($event->getCommand()) {
                case '/horaire@EsisHoraireBot':
                case '/horaire':
                    try {
                        $file = $this->timetable->getTimetableDocument($event->getArgument());
                        $this->api->sendDocument(
                            $chatId,
                            new CURLFile($file, 'application/pdf'),
                            "Voici l'horaire demandé",
                            $replyToMessageId
                        );
                    } catch (InvalidPromotionException | EmptyPromotionException | UnavailableTimetableException $e) {
                        $this->api->sendMessage($chatId, $e->getMessage(), null, false, $replyToMessageId);
                        $this->logger->error($e->getMessage(), $e->getTrace());
                    }
                    break;

                case '/start@EsisHoraireBot':
                case '/start':
                    $keyboard = new ReplyKeyboardMarkup(PromotionService::KEYBOARD_MAKEUP, true);
                    $this->api->sendMessage(
                        $chatId,
                        "Horaire Esis Salama Disponible",
                        null,
                        false,
                        $replyToMessageId,
                        $keyboard
                    );
                    break;

                case '/removeKeyboard@EsisHoraireBot':
                case '/removeKeyboard':
                    $keyboard = new ReplyKeyboardRemove();
                    $this->api->sendMessage($chatId(), null, null, false, null, $keyboard);
                    break;

                case '/subscribe@EsisHoraireBot':
                case '/subscribe':
                    try {
                        $this->subscription->subscribe($event->getMessage(), $event->getArgument());
                        $this->api->sendMessage(
                            $chatId,
                            "✔️ Abonnement effectué avec succès, 
                            vous recevrez automatiquement l'horaire chaque samedi à 9h",
                            null,
                            false,
                            $replyToMessageId
                        );
                    } catch (InvalidPromotionException |
                        SubscriptionEmptyPromotionException |
                        AlreadyHaveActiveSubscriptionException  $e
                    ) {
                        $this->api->sendMessage($chatId, $e->getMessage(), null, false, $replyToMessageId);
                        $this->logger->error($e->getMessage(), $e->getTrace());
                    }
                    break;

                case '/unsubscribe@EsisHoraireBot':
                case '/unsubscribe':
                    try {
                        $this->subscription->unsubscribe($event->getMessage());
                        $this->api->sendMessage(
                            $chatId,
                            "✔ Désabonnement effectué avec succès",
                            null,
                            false,
                            $replyToMessageId
                        );
                    } catch (NonActiveSubscriptionFoundException  $e) {
                        $this->api->sendMessage($chatId, $e->getMessage(), null, false, $replyToMessageId);
                        $this->logger->error($e->getMessage(), $e->getTrace());
                    }
                    break;

                default:
                    $command = $event->getCommand();
                    $this->api->sendMessage($chatId, "⚠️ Commande Indisponible (${command})");
                    break;
            }
        } catch (InvalidArgumentException | Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }
}
