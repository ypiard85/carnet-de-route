<?php

namespace App\Controller\Admin;

use App\Entity\SujetResponse;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SujetResponseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SujetResponse::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
