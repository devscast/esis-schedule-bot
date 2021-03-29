<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\SubscriptionRepository;
use App\Service\Subscription\SubscriptionService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PushNotificationCommand
 * @package App\Command
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class BotTelegramPushCommand extends Command
{
    protected static $defaultName = 'bot:telegram-push';
    private SubscriptionService $service;
    private SubscriptionRepository $repository;

    /**
     * PushNotificationCommand constructor.
     * @param SubscriptionService $service
     * @param SubscriptionRepository $repository
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(SubscriptionService $service, SubscriptionRepository $repository)
    {
        parent::__construct("bot:telegram-push");
        $this->service = $service;
        $this->repository = $repository;
    }

    /**
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    protected function configure()
    {
        $this->setDescription('Send timetable to subscribed users');
    }

    /**
     * TODO: this is an 0(n) notification process, find a way to improve this
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $count = $this->repository->count(['is_active' => true]);
        $subscriptions = $this->repository->findBy(['is_active' => true]);
        $progress = new ProgressBar($output, $count);

        foreach ($subscriptions as $subscription) {
            try {
                $progress->advance();
                $this->service->notify($subscription);
            } catch (\Exception $e) {
                continue;
            }
        }

        $progress->finish();
        return Command::SUCCESS;
    }
}
