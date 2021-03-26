<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class WebhookController
 * @package App\Controller
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class WebhookController
{
    /**
     * @param Request $request
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function index(Request $request): Response
    {
        $data = $request->request->all();
        dd($data);
    }
}
