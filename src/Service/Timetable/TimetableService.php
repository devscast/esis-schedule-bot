<?php

declare(strict_types=1);

namespace App\Service\Timetable;

use App\Entity\Promotion;
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
class TimetableService extends AbstractCachingService
{
    public const CACHE_PATH = '/public/upload/horaire/';
    public const BASE_URL = 'https://www.esisalama.com/assets/upload/horaire/pdf/HORAIRE %s.pdf';

    public function __construct(
        private PromotionService $promotionService,
        KernelInterface $kernel,
        Filesystem $fs
    ) {
        parent::__construct($kernel, $fs);
    }

    /**
     * Get cached timetable file form filesystem
     * @throws EmptyPromotionException
     * @throws InvalidPromotionException
     * @throws UnavailableTimetableException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getTimetableDocument(string $promotion): ?string
    {
        if (mb_strlen($promotion) !== 0) {
            $promotion = $this->promotionService->getPromotionFromName($promotion);
            if (null !== $promotion) {
                $file = sprintf("%sHORAIRE_%s.pdf", $this->root, $promotion->getCode());
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
     * @param Promotion $promotion
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function fetchTimetableDocument(Promotion $promotion): void
    {
        $this->fs->dumpFile(
            filename: $this->root . "HORAIRE_{$promotion->getCode()}.pdf",
            content: @file_get_contents($promotion->getLink())
        );
    }
}
