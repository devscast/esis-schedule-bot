<?php

declare(strict_types=1);

namespace App\Service\Subscription;

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
    private EntityManagerInterface $em;
    private SubscriptionRepository $repository;
    private BotApi $api;
    private TimetableService $timetable;

    /**
     * SubscriptionService constructor.
     * @param EntityManagerInterface $em
     * @param SubscriptionRepository $repository
     * @param TimetableService $timetable
     * @param BotApi $api
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(
        EntityManagerInterface $em,
        SubscriptionRepository $repository,
        TimetableService $timetable,
        BotApi $api
    )
    {
        $this->em = $em;
        $this->repository = $repository;
        $this->api = $api;
        $this->timetable = $timetable;
    }

    /**
     * Create a subscription for push notification,
     * or Reactive existing one
     * @param Message $message
     * @param string $promotion
     * @throws AlreadyHaveActiveSubscriptionException
     * @throws InvalidPromotionException
     * @throws SubscriptionEmptyPromotionException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function subscribe(Message $message, string $promotion): void
    {
        try {
            $promotion = PromotionService::toPromotionCode($promotion);
            $subscribed = $this->repository->findOneBy(['chat_id' => $message->getChat()->getId()]);
            if ($subscribed) {
                if ($subscribed->isActive() === false || $subscribed->getPromotion() !== $promotion) {
                    $subscribed->setPromotion($promotion)->setIsActive(true);
                    $this->em->persist($subscribed);
                } else {
                    throw new AlreadyHaveActiveSubscriptionException();
                }
            } else {
                $subscription = Subscription::fromMessageCommand($message, $promotion);
                $this->em->persist($subscription);
            }
            $this->em->flush();
        } catch (EmptyPromotionException $e) {
            throw new SubscriptionEmptyPromotionException();
        }
    }

    /**
     * Unsubscribe from notification push
     * @param Message $message
     * @throws NonActiveSubscriptionFoundException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function unsubscribe(Message $message): void
    {
        $subscribed = $this->repository->findOneBy([
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
     * @param Subscription $subscription
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
}
