<?php

namespace App\Form;


use App\Entity\Place;
use App\Entity\Categorie;
use App\Entity\City;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\User\UserInterface;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('title')
            ->add('premium', ChoiceType::class, [
                    'choices' => [
                        'NON' => 'Non',
                        'OUI' => 'Oui'
                ]
            ])

            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'brouillon' => 'brouillon',
                    'publié' => 'publié'
                ]
            ])

            ->add('description',  CKEditorType::class)

            ->add('images', FileType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Images',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                }
                ])
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
