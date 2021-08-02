<?php

declare(strict_types=1);

namespace App\Service\Subscription;

use App\DataTransfert\BroadcastData;
use App\Entity\Subscription;
use App\Repository\SubscriptionRepository;
use App\Service\Subscription\Exception\AlreadyHaveActiveSubscriptionException;
use App\Service\Subscription\Exception\EmptyPromotionException as SubscriptionEmptyPromotionException;
use App\Service\Subscription\Exception\NonActiveSubscriptionFoundException;
use App\Service\Timetable\Exception\EmptyPromotionException;
use App\Service\Timetable\Exception\InvalidPromotionException;
use App\Service\Timetable\Exception\UnavailableTimetableException;
use App\Service\Timetable\PromotionService;
use App\Service\Timetable\TimetableService;
use CURLFile;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use TelegramBot\Api\InvalidArgumentException;
use TelegramBot\Api\Types\Message;

/**
 * Class SubscriptionService
 * @package App\Service
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class SubscriptionService
{
    public function __construct(
        private EntityManagerInterface $em,
        private SubscriptionRepository $subscriptionRepository,
        private PromotionService $promotionService,
        private TimetableService $timetable,
        private BotApi $api,
        private LoggerInterface $logger
    ) {
    }

    /**
     * Create a subscription for push notification,
     * or Reactive existing one
     * @throws AlreadyHaveActiveSubscriptionException
     * @throws InvalidPromotionException
     * @throws SubscriptionEmptyPromotionException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function subscribe(Message $message, string $promotion, bool $implicit = false): void
    {
        try {
            $promotion = $this->promotionService->getPromotionFromName($promotion);
            $subscribed = $this->subscriptionRepository->findOneBy(['chat_id' => $message->getChat()->getId()]);
            if ($subscribed) {
                if (!$implicit) {
                    if ($subscribed->isActive() === false || $subscribed->getPromotion() !== $promotion) {
                        $subscribed->setPromotion($promotion->getName())->setIsActive(true);
                        $this->em->persist($subscribed);
                    } else {
                        throw new AlreadyHaveActiveSubscriptionException();
                    }
                }
            } else {
                $subscription = Subscription::fromMessageCommand($message, $promotion->getName());
                $this->em->persist($subscription);
            }
            $this->em->flush();
        } catch (EmptyPromotionException $e) {
            throw new SubscriptionEmptyPromotionException();
        }
    }

    /**
     * Unsubscribe from notification push
     * @throws NonActiveSubscriptionFoundException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function unsubscribe(Message $message): void
    {
        $subscribed = $this->subscriptionRepository->findOneBy([
            'chat_id' => $message->getChat()->getId(),
            'is_active' => true
        ]);

        if ($subscribed) {
            $subscribed->setIsActive(false);
            $this->em->persist($subscribed);
            $this->em->flush();
        } else {
            throw new NonActiveSubscriptionFoundException();
        }
    }

    /**
     * @throws EmptyPromotionException
     * @throws InvalidPromotionException
     * @throws UnavailableTimetableException
     * @throws Exception
     * @throws InvalidArgumentException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function notify(Subscription $subscription): void
    {
        $caption = <<< MESSAGE
Salut %s voici l'horaire de la semaine pour la promotion %s

Pour ne plus recevoir cette notification 
utilisez /unsubscribe
MESSAGE;

        $this->api->sendDocument(
            $subscription->getChatId(),
            new CURLFile($this->timetable->getTimetableDocument($subscription->getPromotion()), 'application/pdf'),
            sprintf($caption, $subscription->getName(), $subscription->getPromotion()),
        );
    }


    /**
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function broadcast(BroadcastData $data): void
    {
        $subscriptions = $data->all ?
            $this->subscriptionRepository->findAll() :
            $this->subscriptionRepository->findBy(['promotion' => $data->promotion]);

        if ($data->file) {
            foreach ($subscriptions as $subscription) {
                try {
                    $this->api->sendDocument(
                        $subscription->getChatId(),
                        new CURLFile($data->attachement, 'application/pdf'),
                        $data->message
                    );
                } catch (\Exception $e) {
                    $this->logger->error($e->getMessage(), $e->getTrace());
                }
            }
        } else {
            foreach ($subscriptions as $subscription) {
                try {
                    $this->api->sendMessage($subscription->getChatId(), $data->message);
                } catch (\Exception $e) {
                    $this->logger->error($e->getMessage(), $e->getTrace());
                }
            }
        }
    }
}
