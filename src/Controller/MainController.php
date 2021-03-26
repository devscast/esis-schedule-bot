<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MainController
 * @package App\Controller
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class MainController
{
    /**
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function index(): Response
    {
        return new RedirectResponse("https://t.me/EsisHoraireBot", Response::HTTP_MOVED_PERMANENTLY);
    }
}
