<?php

declare(strict_types=1);

namespace App\Service\Subscription\Exception;

use Exception;

/**
 * Class NonActiveSubscriptionFoundException
 * @package App\Service\Subscription\Exception
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class NonActiveSubscriptionFoundException extends Exception
{
    protected $message = <<< MESSAGE
Désolé vous n'avez pas un abonnement actif
si vous voulez activer les notifications utilisez la commande /subscribe
MESSAGE;
}
