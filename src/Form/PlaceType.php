<?php

namespace App\Form;


use App\Entity\Place;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('images', FileType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'image',
                'multiple' => true,
                'mapped' => false,
                'required' => true,
            ])
            ->add('city')
            ->add('lat', TextType::class, ['required' => true])
            ->add('longs', TextType::class, ['required' => true])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}
