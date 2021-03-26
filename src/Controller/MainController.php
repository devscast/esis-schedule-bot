<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MainController
 * @package App\Controller
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class MainController
{
    /**
     * @param Request $request
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function index(Request $request): Response
    {
        return new Response(null, Response::HTTP_OK);
    }
}
