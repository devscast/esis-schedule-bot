<?php

declare(strict_types=1);

namespace App\Controller;

use Closure;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CRUDController
 * @package App\Controller
 * @author bernard-ng <ngandubernard@gmail.com>
 */
abstract class CRUDController extends AbstractController
{
    protected EntityManagerInterface $em;
    protected string $entity = '';
    protected string $form = '';
    protected const FILTERABLE_FIELDS = [];
    protected array $route = [
        'index' => '',
        'new' => '',
        'edit' => '',
        'delete' => ''
    ];
    protected array $views = [
        'index' => '',
        'new' => '',
        'edit' => '',
        'delete' => '',
        'form' => ''
    ];
    protected PaginatorInterface $paginator;
    protected EventDispatcherInterface $dispatcher;

    /**
     * CRUDController constructor.
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     * @param EventDispatcherInterface $dispatcher
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function __construct(
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        EventDispatcherInterface $dispatcher
    )
    {
        $this->em = $em;
        $this->paginator = $paginator;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param Request $request
     * @param QueryBuilder|null $qb
     * @param array $context
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function crudIndex(Request $request, ?QueryBuilder $qb = null, array $context = []): Response
    {
        $page = $request->query->getInt('page', 1);
        $field = $request->query->get('filterField', '');
        $value = $request->query->get('filterValue', '');

        if (!$qb) {
            $qb = $this->em->createQueryBuilder();
            $qb->from($this->entity, 'item')->select('item');
        }

        if ($value && $field && in_array($field, array_keys(static::FILTERABLE_FIELDS))) {
            $qb->andWhere("{$field} LIKE :query")->setParameter("query", "%{$value}%");
        }

        $items = $this->paginator->paginate($qb->orderBy("item.id", "DESC"), $page, 50);
        return $this->render($this->views['index'], [
            'items' => $items,
            'search_filters' => static::FILTERABLE_FIELDS,
            $context
        ]);
    }

    /**
     * @param Request $request
     * @param Closure|null $callback
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function crudNew(Request $request, ?Closure $callback = null): Response
    {
        $data = new $this->entity;
        $form = $this->createForm($this->form, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($callback) {
                    $data = $callback($request, $data);
                }

                if ($data !== null) {
                    $this->em->persist($data);
                    $this->em->flush();
                }

                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse(null, Response::HTTP_CREATED);
                }

                $this->addFlash("success", "Donnée rajoutée avec succès !");
                return $this->redirectToRoute($this->route['index']);
            }
            $this->addFlash("error", "les données rentrées ne sont pas valides !");
        }

        $form = $form->createView();
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['html' => $this->renderView($this->views['form'], compact('form'))]);
        }
        return $this->render($this->views['new'], compact('form'));
    }

    /**
     * @param object $item
     * @param Request $request
     * @param Closure|null $callback
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function crudEdit(object $item, Request $request, ?Closure $callback = null): Response
    {
        $form = $this->createForm($this->form, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($callback) {
                    $item = $callback($request, $item);
                }

                if ($item !== null) {
                    $this->em->flush();
                }

                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse(null, Response::HTTP_ACCEPTED);
                }

                $this->addFlash("success", "Donnée éditée avec succès !");
                return $this->redirectToRoute($this->route['index']);
            }

            if (!$request->isXmlHttpRequest()) {
                $this->addFlash("error", "les données rentrées ne sont pas valides !");
            }
        }

        $form = $form->createView();
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['html' => $this->renderView($this->views['form'], compact('form'))]);
        }
        return $this->render($this->route['edit'], compact('form'));
    }

    /**
     * @param object $item
     * @param Request $request
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function crudDelete(object $item, Request $request): Response
    {
        $token = $request->request->get('_token');
        if ($request->isXmlHttpRequest()) {
            $data = json_decode($request->getContent());
            $token = $data->_token;
        }

        if ($this->isCsrfTokenValid('delete_' . $item->getId(), $token)) {
            $this->em->remove($item);
            $this->em->flush();

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(null, Response::HTTP_ACCEPTED);
            }

            $this->addFlash("success", "Donnée supprimée avec succès");
            return $this->redirectToRoute($this->route['index']);
        } else {
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
            }

            $this->addFlash("error", "Le Jeton d'authentification est invalide !");
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }
        return $this->redirectToRoute($this->route['index']);
    }
}
