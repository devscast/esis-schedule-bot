<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Timetable\PromotionService;
use App\Service\Timetable\TimetableService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class BotFetchTimetableCommand
 * @package App\Command
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class BotFetchTimetableCommand extends Command
{
    protected static $defaultName = 'bot:fetch-timetable';
    private TimetableService $service;

    /**
     * BotFetchTimetableCommand constructor.
     * @param TimetableService $service
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(TimetableService $service)
    {
        parent::__construct('bot:fetch-timetable');
        $this->service = $service;
    }

    /**
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    protected function configure()
    {
        $this->setDescription('Fetch time table and cache them on the server');
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
        foreach (PromotionService::PROMOTIONS as $promotion) {
            $this->service->fetchTimetableDocument($promotion);
            $io->text("Timetable Imported => $promotion");
        }

        $io->success("All timetable have been cached on the server !");
        return Command::SUCCESS;
    }
}
