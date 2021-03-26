<?php

declare(strict_types=1);

namespace App\Service\Timetable\Exception;

use Exception;
use Throwable;

/**
 * Class EmptyPromotionException
 * @package App\Service
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class EmptyPromotionException extends Exception
{
    /**
     * EmptyPromotionException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct("Veuillez précisez la promotion S'il vous plait !", $code, $previous);
    }
}
