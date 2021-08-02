<?php

declare(strict_types=1);

namespace App\Service\Timetable;

use App\Entity\Promotion;
use App\Repository\PromotionRepository;
use App\Service\Timetable\Exception\EmptyPromotionException;
use App\Service\Timetable\Exception\InvalidPromotionException;

/**
 * Class PromotionService
 * @package App\Service\Timetable
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class PromotionService
{
    /**
     * map of promotion code and friendly name
     */
    public const PROMOTIONS_FRIENDLY_ABBR = [
        'PREPA_A' => 'PREPA A',
        'PREPA_B' => 'PREPA B',
        'L1_A' => "L1 A",
        'L1_B' => "L1 B",
        'L2' => "L2",
        'L2_AS' => "L2 AS",
        'L2_DESIGN' => "L2 DESIGN",
        'L2_GL' => "L2 GL",
        'L2_MSI' => "L2 MSI",
        'L2_TLC' => "L2 TLS",
        'L3' => "L3",
        'L3_AS' => "L3 AS",
        'L3_DESIGN' => "L3 DESIGN",
        'L3_MSI' => "L3 MSI",
        'L3_SI' => "L3 SI",
        'L3_TLC' => "L3 TLC",
        'L4' => "L4",
        'M1_GL' => "M1 GL",
        'M1_MIAGE' => "M1 MIAGE",
        'M1_RM' => "M1 RM",
        'M2_GL' => "M2 GL",
        'M2_MIAGE' => "M2 MIAGE",
        'M2_RM' => "M2 RM",
    ];

    public function __construct(private PromotionRepository $repository)
    {
    }

    public function getKeyboardMarkup(): array
    {
        $promotions = $this->repository->findAll();
        $names = array_map(fn(Promotion $promotion) => $promotion->getName(), $promotions);
        return array_chunk($names, 3);
    }

    /**
     * @throws EmptyPromotionException
     * @throws InvalidPromotionException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getPromotionFromName(string $name): ?Promotion
    {
        if (mb_strlen($name) !== 0) {
            $name = strtoupper(trim($name));
            $promotion = $this->repository->findOneBy(['name' => $name]);
            if ($promotion !== null) {
                return $promotion;
            }
            throw new InvalidPromotionException($name);
        }
        throw new EmptyPromotionException();
    }
}
