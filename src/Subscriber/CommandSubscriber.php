<?php

declare(strict_types=1);

namespace App\Subscriber;

use App\Event\CommandEvent;
use App\Event\ImplicitSubscriptionEvent;
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
    public function __construct(
        private TimetableService $timetable,
        private SubscriptionService $subscription,
        private PromotionService $promotionService,
        private LoggerInterface $logger,
        private BotApi $api
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CommandEvent::class => 'onCommand',
            ImplicitSubscriptionEvent::class => 'onImplicitSubscription'
        ];
    }

    public function onCommand(CommandEvent $event): void
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
                            chatId: $chatId,
                            document: new CURLFile($file, 'application/pdf'),
                            caption: "Voici l'horaire demandé",
                            replyToMessageId: $replyToMessageId
                        );
                    } catch (InvalidPromotionException | EmptyPromotionException | UnavailableTimetableException $e) {
                        $this->api->sendMessage(
                            chatId: $chatId,
                            text: $e->getMessage(),
                            disablePreview: false,
                            replyToMessageId: $replyToMessageId
                        );
                        $this->logger->error($e->getMessage(), $e->getTrace());
                    }
                    break;

                case '/start@EsisHoraireBot':
                case '/start':
                    $this->api->sendMessage(
                        chatId: $chatId,
                        text: null,
                        disablePreview: false,
                        replyMarkup: new ReplyKeyboardRemove()
                    );
                    $this->api->sendMessage(
                        chatId: $chatId,
                        text: "Horaire Esis Salama Disponible",
                        disablePreview: false,
                        replyToMessageId: $replyToMessageId,
                        replyMarkup: new ReplyKeyboardMarkup(
                            keyboard: $this->promotionService->getKeyboardMarkup(),
                            oneTimeKeyboard: true
                        )
                    );
                    break;

                case '/subscribe@EsisHoraireBot':
                case '/subscribe':
                    try {
                        $this->subscription->subscribe($event->getMessage(), $event->getArgument());
                        $this->api->sendMessage(
                            chatId: $chatId,
                            text: "✔️ Abonnement effectué avec succès, 
                            vous recevrez automatiquement l'horaire chaque samedi à 9h",
                            disablePreview: false,
                            replyToMessageId: $replyToMessageId
                        );
                    } catch (InvalidPromotionException |
                        SubscriptionEmptyPromotionException |
                        AlreadyHaveActiveSubscriptionException  $e
                    ) {
                        $this->api->sendMessage(
                            chatId: $chatId,
                            text: $e->getMessage(),
                            disablePreview: false,
                            replyToMessageId: $replyToMessageId
                        );
                        $this->logger->error($e->getMessage(), $e->getTrace());
                    }
                    break;

                case '/unsubscribe@EsisHoraireBot':
                case '/unsubscribe':
                    try {
                        $this->subscription->unsubscribe($event->getMessage());
                        $this->api->sendMessage(
                            chatId: $chatId,
                            text: "✔ Désabonnement effectué avec succès",
                            disablePreview: false,
                            replyToMessageId: $replyToMessageId
                        );
                    } catch (NonActiveSubscriptionFoundException  $e) {
                        $this->api->sendMessage(
                            chatId: $chatId,
                            text: $e->getMessage(),
                            disablePreview: false,
                            replyToMessageId: $replyToMessageId
                        );
                        $this->logger->error($e->getMessage(), $e->getTrace());
                    }
                    break;

                default:
                    $command = $event->getCommand();
                    $this->api->sendMessage(
                        chatId: $chatId,
                        text: "⚠️ Commande Indisponible ($command)"
                    );
                    break;
            }
        } catch (InvalidArgumentException | Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }

    public function onImplicitSubscription(ImplicitSubscriptionEvent $event): void
    {
        try {
            $this->subscription->subscribe(
                message: $event->getMessage(),
                promotion: $event->getArgument(),
                implicit: true
            );
        } catch (SubscriptionEmptyPromotionException | AlreadyHaveActiveSubscriptionException $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }
    }
}
