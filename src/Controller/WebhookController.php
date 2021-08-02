<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\PlayLoadService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class WebhookController
 * @package App\Controller
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class WebhookController
{
    public function index(Request $request, PlayLoadService $service): Response
    {
        $service->negotiate($request);
        return new Response(null, Response::HTTP_OK);
    }
}
