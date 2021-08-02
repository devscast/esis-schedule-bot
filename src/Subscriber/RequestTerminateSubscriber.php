<?php

declare(strict_types=1);

namespace App\Subscriber;

use App\Entity\Request;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\TerminateEvent;

/**
 * Class RequestTerminateSubscriber
 * @package App\Subscriber
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class RequestTerminateSubscriber implements EventSubscriberInterface
{

    public function __construct(private EntityManagerInterface $em)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.terminate' => 'onKernelTerminate',
        ];
    }

    public function onKernelTerminate(TerminateEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();
        $micro = (int)$request->server->get('REQUEST_TIME_FLOAT');

        if ($request->getMethod() === 'POST') {
            $log = (new Request())
                ->setMethod($request->getMethod())
                ->setPayload($request->getContent())
                ->setRequestedAt((new DateTimeImmutable())->setTimestamp($micro))
                ->setRespondedAt(new DateTimeImmutable('now'))
                ->setResponseCode($response->getStatusCode());

            $this->em->persist($log);
            $this->em->flush();
        }
    }
}
