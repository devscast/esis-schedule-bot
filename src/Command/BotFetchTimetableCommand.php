<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\PromotionRepository;
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

    public function __construct(
        private TimetableService $service,
        private PromotionRepository $repository
    ) {
        parent::__construct('bot:fetch-timetable');
    }

    protected function configure()
    {
        $this->setDescription('Fetch time table and cache them on the server');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $promotions = $this->repository->findAll();
        foreach ($promotions as $promotion) {
            $this->service->fetchTimetableDocument($promotion);
            $io->text("Timetable Imported => {$promotion->getCode()}");
        }

        $io->success("All timetable have been cached on the server !");
        return Command::SUCCESS;
    }
}
