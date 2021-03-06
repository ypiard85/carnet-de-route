<?php
namespace App\Controller\Admin;

//https://www.youtube.com/watch?v=MS4LICZ1j0s
use App\Entity\Place;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class PlaceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Place::class;
    }


    public function configureFields(string $pageName): iterable
    {

        $fileds = [
            AssociationField::new('user'),
            TextField::new('title'),
            ChoiceField::new('statut')
                ->setChoices(['brouillon' => 'brouillon', 'publié' => 'publié'])
            ,
            ChoiceField::new('premium')
                ->setChoices(['Oui' => 'Oui', 'Non' => 'Non'])
            ,
            TextField::new('lat'),
            TextField::new('longs'),
            AssociationField::new('city'),
            TextEditorField::new('description'),
            AssociationField::new('categorie'),
            AssociationField::new('images'),
        ];



        return $fileds;
    }

}
