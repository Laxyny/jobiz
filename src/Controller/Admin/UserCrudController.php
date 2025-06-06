<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
{
    return [
        IdField::new('id', 'ID')->hideOnForm(),
        TextField::new('email', 'Email'),
        TextField::new('firstname', 'Prénom'),
        TextField::new('lastname', 'Nom'),
        AssociationField::new('jobapplications', 'Candidatures')->hideOnForm(),
    ];
}
}
