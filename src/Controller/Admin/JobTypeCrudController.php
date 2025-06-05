<?php

namespace App\Controller\Admin;

use App\Entity\JobType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class JobTypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return JobType::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),
            TextField::new('name', 'Nom du type'),
            AssociationField::new('jobs', 'Offres d\'emploi')->hideOnForm(),
        ];
    }
}
