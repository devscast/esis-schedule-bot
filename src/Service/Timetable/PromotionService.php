<?php

declare(strict_types=1);

namespace App\Service\Timetable;

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
     * Keyboard promotion suggestion for telegram app
     */
    public const KEYBOARD_MAKEUP = [
        ['PREPA A', 'PREPA B', 'L1 A'],
        ['L1 B', 'L2 AS', 'L2 DESIGN'],
        ['L2', 'L2 MSI', 'L2 TLC'],
        ['L2 GL', 'L3 AS', 'L3 DESIGN'],
        ['L3', 'L3 SI', 'L3 TLC'],
        ['L3 MSI', 'M1 GL', 'M1 MIAGE'],
        ['L4', 'M1 GL', 'M1 MIAGE'],
        ['M1 RM', 'M2 GL', 'M2 MIAGE'],
        ['M2 RM']
    ];

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

    /**
     * all available promotions of esis salama
     */
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

    /**
     * Get promotion code from a friendly name
     * @param string|null $name
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public static function fromFriendlyAbbr(?string $name = ""): ?string
    {
        $name = $name === null ? "" : $name;
        $code = array_search(trim(strtoupper($name)), self::PROMOTIONS_FRIENDLY_ABBR);
        return $code === false ? self::fromCode($name) : $code;
    }

    /**
     * @param string $name
     * @return string|null
     * @throws EmptyPromotionException
     * @throws InvalidPromotionException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public static function toPromotionCode(string $name): ?string
    {
        if (mb_strlen(strtoupper(trim($name))) !== 0) {
            $code = self::fromFriendlyAbbr($name);
            if ($code !== null) {
                return $code;
            }
            throw new InvalidPromotionException($name);
        }
        throw new EmptyPromotionException();
    }

    /**
     * @param string $name
     * @return string|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public static function fromCode(string $name): ?string
    {
        if (isset(self::PROMOTIONS[$name])) {
            return $name;
        }
        return null;
    }

    /**
     * %20 - represent a space character in URL encoded format
     * this is because of esis website typo in timetable name file
     * @param string $name
     * @return string|null
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public static function fromFileSystem(string $name): ?string
    {
        return $name === 'PREPA_B' ? 'PREPA_%20B' : $name;
    }
}
