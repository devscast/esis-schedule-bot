<?php

declare(strict_types=1);

namespace App\Service\Subscription\Exception;

use Exception;

/**
 * Class EmptyPromotionException
 * @package App\Service\Subscription\Exception
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class EmptyPromotionException extends Exception
{
    protected $message = <<< MESSAGE
⚠️ Veuillez préciser la promotion pour laquelle vous voulez recevoir automatiquement l'horaire !
 
Par exemple : /subscribe L2 AS

utilisez /unsubscribe pour vous désabonner
MESSAGE;
}
