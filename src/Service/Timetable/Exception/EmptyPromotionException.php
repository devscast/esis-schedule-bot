<?php

declare(strict_types=1);

namespace App\Service\Timetable\Exception;

use InvalidArgumentException;

/**
 * Class EmptyPromotionException
 * @package App\Service\Timetable\Exception
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class EmptyPromotionException extends InvalidArgumentException
{
    protected $message = <<< MESSAGE
⚠️ Veuillez préciser la promotion pour laquelle vous souhaitez obtenir l'horaire
 
Par exemple : /horaire PREPA B

Utilisez /start pour obtenir la liste d'horaire disponible
MESSAGE;
}
