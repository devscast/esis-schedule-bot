<?php

declare(strict_types=1);

namespace App\Service\Timetable\Exception;

use Exception;
use Throwable;

/**
 * Class InvalidPromotionException
 * @package App\Service
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class InvalidPromotionException extends Exception
{
    /**
     * InvalidPromotionException constructor.
     * @param $message
     * @param int $code
     * @param Throwable|null $previous
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        parent::__construct(sprintf("⚠️ Désolé la promotion (%s) n'est pas reconnue !", $message), $code, $previous);
    }
}
