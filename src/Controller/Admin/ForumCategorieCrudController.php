<?php

namespace App\Controller\Admin;

use App\Entity\ForumCategorie;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ForumCategorieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ForumCategorie::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('categorie'),
            TextEditorField::new('description'),
        ];
    }

}
