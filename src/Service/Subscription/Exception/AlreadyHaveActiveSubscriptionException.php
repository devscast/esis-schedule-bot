<?php

declare(strict_types=1);

namespace App\Service\Subscription\Exception;

use Exception;

/**
 * Class AlreadyHaveActiveSubscriptionException
 * @package App\Service\Subscription\Exception
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class AlreadyHaveActiveSubscriptionException extends Exception
{
    protected $message = <<< MESSAGE
⚠️ Vous avez déjà un abonnement actif aux notifications instantanées du bot
Si vous voulez désactiver les notifications 

utilisez /unsubscribe
MESSAGE;
}
