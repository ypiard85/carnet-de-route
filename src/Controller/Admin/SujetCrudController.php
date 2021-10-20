<?php

namespace App\Controller\Admin;

use App\Entity\Sujet;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SujetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sujet::class;
    }


    public function configureFields(string $pageName): iterable
    {

        $fields = [
            TextField::new('title'),
            TextField::new('user', 'utilisateur')->hideOnForm(),
            DateTimeField::new('created_at', 'Publier le')->hideOnForm(),
            AssociationField::new('categorie'),
            TextEditorField::new('content')
        ];



        return $fields;
    }

}
