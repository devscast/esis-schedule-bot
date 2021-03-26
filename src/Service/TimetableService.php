<?php

declare(strict_types=1);

namespace App\Service;

/**
 * Class EsisTimetableService
 * @package App\Service
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class TimetableService
{
    public const BASE_URL = 'https://www.esisalama.com/assets/upload/horaire/pdf/HORAIRE %s.pdf';
    public const PROMOTIONS = [
        'PREPA_A',
        'PREPA_B',
        'L1_A',
        'L2_B',
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

    /**
     * @param string $promotion
     * @return string
     * @throws InvalidPromotionException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getTimetableLinkByPromotion(string $promotion): string
    {
        if (in_array($promotion, self::PROMOTIONS)) {
            return sprintf(self::BASE_URL, $promotion);
        } else {
            throw new InvalidPromotionException();
        }
    }
}
