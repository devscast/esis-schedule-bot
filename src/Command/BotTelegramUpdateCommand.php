<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;

/**
 * Class BotTelegramUpdateCommand
 * @package App\Command
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class BotTelegramUpdateCommand extends Command
{
    protected static $defaultName = 'bot:telegram-update';
    private BotApi $api;

    /**
     * BotSetTelegramWebhookCommand constructor.
     * @param BotApi $api
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(BotApi $api)
    {
        parent::__construct('bot:telegram-update');
        $this->api = $api;
    }

    /**
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    protected function configure()
    {
        $this->setDescription('Get a update from telegram bot');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $data = $this->api->getUpdates();
            foreach ($data as $datum) {
                $io->text($datum->getUpdateId());
            }
        } catch (Exception $e) {
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}
