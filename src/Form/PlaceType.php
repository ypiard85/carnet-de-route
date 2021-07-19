<?php

namespace App\Form;


use App\Entity\Place;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
                'required' => false,
            ])
            ->add('city')
            ->add('categorie', EntityType::class, ['class' => Categorie::class,  'choice_label' => 'nom', ])
            ->add('lat', TextType::class, ['required' => true, 'label' => 'Latitude'])
            ->add('longs', TextType::class, ['required' => true, 'label' => 'longitude'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}
