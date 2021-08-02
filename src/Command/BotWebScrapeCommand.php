<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Timetable\ScrapingService;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class BotFetchTimetableCommand
 * @package App\Command
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class BotWebScrapeCommand extends Command
{
    protected static $defaultName = 'bot:web-scrape';

    public function __construct(private ScrapingService $service)
    {
        parent::__construct('bot:web-scrape');
    }

    protected function configure()
    {
        $this->setDescription('Fetch time table pages and cache them on the server');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        foreach (ScrapingService::VACATIONS as $vacation) {
            try {
                $this->service->fetchTimetableHTMLDocument($vacation);
                $io->text("HTML Document imported => $vacation");
            } catch (Exception $e) {
                $io->warning($e->getMessage());
                continue;
            }
        }

        $io->success("All Documents have been imported !");
        return Command::SUCCESS;
    }
}
