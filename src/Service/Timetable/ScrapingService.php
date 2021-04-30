<?php

declare(strict_types=1);

namespace App\Service\Timetable;

use App\Service\Timetable\Exception\InvalidVacationException;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ScrapingService
 * @package App\Service\Timetable
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class ScrapingService extends AbstractCachingService
{
    public const CACHE_PATH = '/public/upload/html/';
    public const VACATIONS = ['jour', 'soir', 'master'];
    public const BASE_URL = "https://www.esisalama.com/index.php?module=horaire&vacation=%s";

    public const XPATH = [
        'PREPA_A' => ['vacation' => 'jour', 'path' => 'div#collapse1 > table'],
        'PREPA_B' => ['vacation' => 'jour', 'path' => 'div#collapse2 > table'],
        'L1_A' => ['vacation' => 'jour', 'path' => 'div#collapse3 > table'],
        'L1_B' => ['vacation' => 'jour', 'path' => 'div#collapse4 > table'],
        'L2' => ['vacation' => 'soir', 'path' => 'div#collapse1 > table'],
        'L2_AS' => ['vacation' => 'jour', 'path' => 'div#collapse5 > table'],
        'L2_DESIGN' => ['vacation' => 'jour', 'path' => 'div#collapse9 > table'],
        'L2_GL' => ['vacation' => 'jour', 'path' => 'div#collapse7 > table'],
        'L2_MSI' => ['vacation' => 'jour', 'path' => 'div#collapse12 > table'],
        'L2_TLC' => ['vacation' => 'jour', 'path' => 'div#collapse6 > table'],
        'L3' => ['vacation' => 'soir', 'path' => 'div#collapse2 > table'],
        'L3_AS' => ['vacation' => 'jour', 'path' => 'div#collapse10 > table'],
        'L3_DESIGN' => ['vacation' => 'jour', 'path' => 'div#collapse14 > table'],
        'L3_MSI' => ['vacation' => 'jour', 'path' => 'div#collapse12 > table'],
        'L3_SI' => ['vacation' => 'jour', 'path' => 'div#collapse13 > table'],
        'L3_TLC' => ['vacation' => 'jour', 'path' => 'div#collapse11 > table'],
        'L4' => ['vacation' => 'soir', 'path' => 'div#collapse3 > table'],
        'M1_GL' => ['vacation' => 'master', 'path' => 'div#collapse2 > table'],
        'M1_MIAGE' => ['vacation' => 'master', 'path' => 'div#collapse1 > table'],
        'M1_RM' => ['vacation' => 'master', 'path' => 'div#collapse3 > table'],
        'M2_GL' => ['vacation' => 'master', 'path' => 'div#collapse5 > table'],
        'M2_MIAGE' => ['vacation' => 'master', 'path' => 'div#collapse4 > table'],
        'M2_RM' => ['vacation' => 'master', 'path' => 'div#collapse6 > table'],
    ];

    /**
     * Download pages from esisalama.com website and cache them
     * on the server for performance issues
     * @param string $vacation
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function fetchTimetableHTMLDocument(string $vacation)
    {
        if (in_array($vacation, self::VACATIONS)) {
            $this->fs->dumpFile(
                $this->root . "{$vacation}.html",
                @file_get_contents(sprintf(self::BASE_URL, $vacation))
            );
        } else {
            throw new InvalidVacationException($vacation);
        }
    }

    /**
     * @param string $vacation
     * @return array
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function serializeHTMLDocument(string $vacation): array
    {
        if (in_array($vacation, self::VACATIONS)) {
            $document = @file_get_contents($this->root . "{$vacation}.html");
            $crawler = new Crawler($document);
            $crawler = $crawler->filter('div#collapse2 > .bloc-table-horaire');

            $table = $crawler->filter('table')->filter('tr')->each(function ($tr) {
                return $tr->filter('td')->each(function ($td) {
                    return trim($td->text());
                });
            });
        }
        throw new InvalidVacationException($vacation);
    }
}
