<?php

declare(strict_types=1);

namespace App\Form;

use App\DataTransfert\BroadcastData;
use App\Repository\PromotionRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BroadcastType
 * @package App\Form
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class BroadcastType extends AbstractType
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
            ->add('message', TextareaType::class)
            ->add('all', CheckboxType::class, [
                'label' => 'Envoyer à tout le monde',
                'required' => false
            ])
            ->add('promotion', ChoiceType::class, [
                'choices' => $this->repository->getPromotionChoiceList(),
                'required' => false
            ])
            ->add('file', FileType::class, [
                'label' => 'Document',
                'required' => false
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BroadcastData::class,
        ]);
    }
}
