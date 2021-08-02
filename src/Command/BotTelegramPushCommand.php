<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\SubscriptionRepository;
use App\Service\Subscription\SubscriptionService;
use Exception;
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

    public function __construct(
        private SubscriptionService $service,
        private SubscriptionRepository $repository
    ) {
        parent::__construct("bot:telegram-push");
    }

    protected function configure()
    {
        $this->setDescription('Send timetable to subscribed users');
    }

    /**
     * TODO: this is an 0(n) notification process, find a way to improve this
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
            } catch (Exception) {
                continue;
            }
        }

        $progress->finish();
        return Command::SUCCESS;
    }
}
