<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\SubscriptionRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PushNotificationCommand
 * @package App\Command
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class PushNotificationCommand extends Command
{
    protected static $defaultName = 'app:push-notification';

    /**
     * PushNotificationCommand constructor.
     * @param SubscriptionRepository $repository
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(SubscriptionRepository $repository)
    {
        parent::__construct("app:push-notification");
    }

    /**
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    protected function configure()
    {
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
    }
}
