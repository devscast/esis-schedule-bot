<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Request as RequestLog;
use App\Repository\RequestRepository;
use DateTimeImmutable;
use League\Period\Period;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MainController
 * @package App\Controller
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class MainController extends CRUDController
{
    /**
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function index(): Response
    {
        return new RedirectResponse("https://t.me/EsisHoraireBot", Response::HTTP_TEMPORARY_REDIRECT);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @param Request $request
     * @param RequestRepository $repository
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function dashboard(Request $request, RequestRepository $repository): Response
    {
        $this->entity = RequestLog::class;
        $this->views['index'] = 'index.html.twig';

        $days = $repository->countByPeriod($this->getInterval('DAY'));
        $weeks = $repository->countByPeriod($this->getInterval('WEEK'));
        $months = $repository->countByPeriod($this->getInterval('MONTH'));

        return $this->crudIndex($request, null, compact('days', 'weeks', 'months'));
    }

    /**
     * @param string $period
     * @return array
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    private function getInterval(string $period): array
    {
        $interval = Period::before(new DateTimeImmutable('now +1 DAY'), "1 {$period}");
        [$start, $end] = explode("/", $interval->toIso8601("Y-m-d"));
        return [$start, $end];
    }
}
