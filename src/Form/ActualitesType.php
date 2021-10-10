<?php

namespace App\Form;

use App\Entity\Actualites;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ActualitesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('name', FileType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Images d\'en tête',
                'multiple' => false,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new NotBlank(),
                    new Image([
                        'maxSize' => 800000000,
                    ]),
                ],
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'Contenu'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Actualites::class,
        ]);
    }
}
