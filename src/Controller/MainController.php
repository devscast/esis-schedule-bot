<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MainController
 * @package App\Controller
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class MainController extends AbstractController
{
    /**
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function index(): Response
    {
        return new RedirectResponse("https://t.me/EsisHoraireBot", Response::HTTP_MOVED_PERMANENTLY);
    }

    /**
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function dashboard(): Response
    {
        return $this->render("");
    }

    /**
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function subscription(): Response
    {
        return $this->render("");
    }
}
