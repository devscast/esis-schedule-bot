<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Subscription;
use App\Repository\PromotionRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SubscriptionType
 * @package App\Form
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class SubscriptionType extends AbstractType
{
    public function __construct(private PromotionRepository $repository)
    {
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('chat_id', TextType::class, ['label' => 'Chat ID'])
            ->add('promotion', ChoiceType::class, [
                'choices' => $this->repository->getPromotionChoiceList()
            ])
            ->add('is_active', CheckboxType::class, ['label' => 'Abonnement actif']);
    }

    /**
     * @param OptionsResolver $resolver
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subscription::class,
        ]);
    }
}
