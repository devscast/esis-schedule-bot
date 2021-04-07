<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package App\Controller
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class UserController extends CRUDController
{
    protected string $entity = User::class;
    protected string $form = UserType::class;
    protected const FILTERABLE_FIELDS = [];
    protected array $route = [
        'index' => 'app_user_index',
        'new' => 'app_user_new',
        'edit' => 'app_user_edit',
        'delete' => 'app_user_delete'
    ];
    protected array $views = [
        'index' => 'user/index.html.twig',
        'form' => "_includes/_forms.html.twig"
    ];


    /**
     * @param Request $request
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function index(Request $request): Response
    {
        return $this->crudIndex($request);
    }

    /**
     * @param Request $request
     * @param User $item
     * @return Response
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function delete(Request $request, User $item): Response
    {
        return $this->crudDelete($item, $request);
    }
}
