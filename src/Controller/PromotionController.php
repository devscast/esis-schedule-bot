<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Promotion;
use App\Form\PromotionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 * Class PromotionController
 * @package App\Controller
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class PromotionController extends CRUDController
{
    protected string $entity = Promotion::class;
    protected string $form = PromotionType::class;
    protected const FILTERABLE_FIELDS = [];
    protected array $route = [
        'index' => 'app_promotion_index',
        'new' => 'app_promotion_new',
        'edit' => 'app_promotion_edit',
        'delete' => 'app_promotion_delete'
    ];
    protected array $views = [
        'index' => 'promotion/index.html.twig',
        'new' => 'promotion/new.html.twig',
        'edit' => 'promotion/edit.html.twig',
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

    public function edit(Promotion $item, Request $request): Response
    {
        return $this->crudEdit($item, $request);
    }

    public function delete(Promotion $item, Request $request): Response
    {
        return $this->crudDelete($item, $request);
    }
}
