<?php

declare(strict_types=1);

namespace App\Service\Timetable\Exception;

use InvalidArgumentException;
use Throwable;

/**
 * Class InvalidPromotionException
 * @package App\Service
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class InvalidPromotionException extends InvalidArgumentException
{
    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            message: sprintf("⚠️ Désolé la promotion (%s) n'est pas reconnue !", $message),
            code: $code,
            previous: $previous
        );
    }
}
