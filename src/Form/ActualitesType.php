<?php

namespace App\Form;

use App\Entity\Actualites;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActualitesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('name', FileType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Images d\'en tête',
                'multiple' => false,
                'mapped' => false,
                'required' => false,
            ])
            ->add('content', CKEditorType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Actualites::class,
        ]);
    }
}
