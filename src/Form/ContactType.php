<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', TextType::class, [
                'label' => false,
                'required' => true
            ])
            ->add('nom', TextType::class, [
                'label' => false,
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'required' => true
            ])
            ->add('objet', TextType::class, [
                'label' => false,
                'required' => true
            ])
            ->add('message', TextareaType::class, [
                'attr' => [
                    'rows' => 10
                ],

                'label' => false,
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

        ]);
    }
}
