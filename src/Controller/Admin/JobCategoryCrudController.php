<?php

namespace App\Controller\Admin;

use App\Entity\JobCategory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class JobCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return JobCategory::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),
            TextField::new('name', 'Nom de la catégorie'),
            AssociationField::new('jobs', 'Offres d\'emploi')->setFormTypeOption('by_reference', false),
        ];
    }
}
