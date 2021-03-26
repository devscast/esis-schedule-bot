<?php

declare(strict_types=1);

namespace App\Service\Timetable\Exception;

use Exception;

/**
 * Class UnavailableTimetableException
 * @package App\Service
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class UnavailableTimetableException extends Exception
{
    protected $message = <<< MESSAGE
Désolé l'horaire pour votre promotion n'est pas encore disponible ici, 
vérifiez directement sur le site d'Esis comme d'habitude 🤧🤧 ou réessayez plus tard
MESSAGE;

}
