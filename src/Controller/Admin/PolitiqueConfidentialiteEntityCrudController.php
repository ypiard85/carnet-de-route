<?php

namespace App\Controller\Admin;

use App\Entity\PolitiqueConfidentialiteEntity;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PolitiqueConfidentialiteEntityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PolitiqueConfidentialiteEntity::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextEditorField::new('content'),
        ];
    }

}
