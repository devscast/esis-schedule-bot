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
    private EntityManagerInterface $em;

    /**
     * RequestTerminateSubscriber constructor.
     * @param EntityManagerInterface $em
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return string[]
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.terminate' => 'onKernelTerminate',
        ];
    }

    /**
     * @param TerminateEvent $event
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function onKernelTerminate(TerminateEvent $event)
    {
        $request = $event->getRequest();
        $response = $event->getResponse();


        $micro = (int) $request->server->get('REQUEST_TIME_FLOAT');

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
