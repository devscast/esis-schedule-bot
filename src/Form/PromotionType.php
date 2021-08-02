<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Promotion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PromotionType
 * @package App\Form
 * @author bernard-ng <ngandubernard@gmail.com>
 */
class PromotionType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('code', TextType::class, ['label' => 'Code'])
            ->add('link', UrlType::class, ['label' => 'Lien du document'])
            ->add('file', FileType::class, ['label' => 'Fichier'])
            ->add('updated_at', DateTimeType::class, [
                'label' => 'Mise à jour',
                'widget' => 'single_text'
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Promotion::class,
        ]);
    }
}
