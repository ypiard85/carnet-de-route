<?php

namespace App\Controller\Admin;

use App\Entity\ForumCategorie;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ForumCategorieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ForumCategorie::class;
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
