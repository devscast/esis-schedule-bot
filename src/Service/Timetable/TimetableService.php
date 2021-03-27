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
     * Get cached timetable file form filesystem
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
            $file = sprintf("%sHORAIRE_%s.pdf", $this->root, PromotionService::fromFileSystem($promotion));
            if (in_array($promotion, PromotionService::PROMOTIONS)) {
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
     * Download file from esisalama.com website and cache them
     * on the server for performance issues
     * @param string $promotion
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function fetchTimetableDocument(string $promotion)
    {
        $promotion = PromotionService::fromFileSystem($promotion);
        $this->fs->dumpFile(
            $this->root . "HORAIRE_{$promotion}.pdf",
            @file_get_contents(sprintf(TimetableService::BASE_URL, "%20$promotion"))
        );
    }
}
