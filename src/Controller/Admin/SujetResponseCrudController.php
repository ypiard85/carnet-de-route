<?php

namespace App\Controller\Admin;

use App\Entity\SujetResponse;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class SujetResponseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SujetResponse::class;
    }


    public function configureFields(string $pageName): iterable
    {




        $fields = [
            TextField::new('content'),
            AssociationField::new('user', 'utilisateur'),
            DateTimeField::new('created_at', 'Publier le')->hideOnForm(),
        ];

        if($pageName == Crud::PAGE_INDEX){
            $fields[]  = TextField::new('content');
        }else if($pageName == Crud::PAGE_EDIT){
            $fields[] = TextEditorField::new('content');
         }

        return $fields;
    }

}
