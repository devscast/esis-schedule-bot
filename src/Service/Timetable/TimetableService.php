<?php

declare(strict_types=1);

namespace App\Service\Timetable;

use App\Service\Timetable\Exception\EmptyPromotionException;
use App\Service\Timetable\Exception\InvalidPromotionException;
use App\Service\Timetable\Exception\UnavailableTimetableException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class EsisTimetableService
 * @package App\Service
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class TimetableService
{
    public const CACHE_PATH = '/public/upload/horaire/';
    public const BASE_URL = 'https://www.esisalama.com/assets/upload/horaire/pdf/HORAIRE%s.pdf';
    public const PROMOTIONS = [
        'PREPA_A',
        'PREPA_B',
        'L1_A',
        'L1_B',
        'L2',
        'L2_AS',
        'L2_DESIGN',
        'L2_GL',
        'L2_MSI',
        'L2_TLC',
        'L3',
        'L3_AS',
        'L3_DESIGN',
        'L3_MSI',
        'L3_SI',
        'L3_TLC',
        'L4',
        'M1_GL',
        'M1_MIAGE',
        'M1_RM',
        'M2_GL',
        'M2_MIAGE',
        'M2_RM',
    ];
    private string $root;
    private Filesystem $fs;

    /**
     * TimetableService constructor.
     * @param KernelInterface $kernel
     * @param Filesystem $fs
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(KernelInterface $kernel, Filesystem $fs)
    {
        $root = $kernel->getProjectDir() . TimetableService::CACHE_PATH;
        if (!file_exists($root)) {
            mkdir($root, 0777, true);
        }
        $this->root = $root;
        $this->fs = $fs;
    }

    /**
     * @param string $promotion
     * @return string
     * @throws EmptyPromotionException
     * @throws InvalidPromotionException
     * @throws UnavailableTimetableException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getTimetableDocument(string $promotion): ?string
    {
        if (mb_strlen($promotion) !== 0) {
            $promotion = $promotion === 'PREPA_B' ? 'PREPA_%20B' : $promotion; // because of esis website typo
            $file = $this->root . "HORAIRE_{$promotion}.pdf";
            if (in_array($promotion, self::PROMOTIONS) || $promotion === 'PREPA_%20B') {
                if (file_exists($file)) {
                    return $file;
                }
                throw new UnavailableTimetableException();
            }
            throw new InvalidPromotionException($promotion);
        }
        throw new EmptyPromotionException();
    }

    /**
     * @param string $promotion
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function fetchTimetableDocument(string $promotion)
    {
        $promotion = $promotion === 'PREPA_B' ? 'PREPA_%20B' : $promotion; // because of esis website typo
        $this->fs->dumpFile(
            $this->root . "HORAIRE_{$promotion}.pdf",
            @file_get_contents(sprintf(TimetableService::BASE_URL, "%20$promotion"))
        );
    }
}
