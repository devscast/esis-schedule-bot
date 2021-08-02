<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Subscription;
use App\Form\SubscriptionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 * Class SubscriptionController
 * @package App\Controller
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class SubscriptionController extends CRUDController
{
    protected string $entity = Subscription::class;
    protected string $form = SubscriptionType::class;
    protected const FILTERABLE_FIELDS = [];
    protected array $route = [
        'index' => 'app_subscription_index',
        'new' => 'app_subscription_new',
        'edit' => 'app_subscription_edit',
        'delete' => 'app_subscription_delete'
    ];
    protected array $views = [
        'index' => 'subscription/index.html.twig',
        'new' => 'subscription/new.html.twig',
        'edit' => 'subscription/edit.html.twig',
        'form' => "_includes/_forms.html.twig"
    ];

    public function index(Request $request): Response
    {
        return $this->crudIndex($request);
    }

    public function new(Request $request): Response
    {
        return $this->crudNew($request);
    }

    public function edit(Request $request, Subscription $item): Response
    {
        return $this->crudEdit($item, $request);
    }

    public function delete(Request $request, Subscription $item): Response
    {
        return $this->crudDelete($item, $request);
    }
}
