<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserType extends AbstractType
{



    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add('email')
            ->add('pseudo')
            ->add('avatar', FileType::class, ['attr' => ['class' => 'form-group' ], 'label' => false, 'data_class' => null, 'required' => false])
            ->add('description', TextareaType::class, ['attr' => ['rows' => '10'] ] )
            ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {


        $resolver->setDefaults([
            'data_class' => User::class,
            'avatar' => UserInterface::class,
        ]);
    }
}
