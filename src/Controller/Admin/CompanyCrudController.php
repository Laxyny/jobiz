<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;


class CompanyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Company::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
        IdField::new('id', 'ID')->hideOnForm(),
        TextField::new('name', 'Nom de l\'entreprise'),
        TextareaField::new('description', 'Description'),
        TextField::new('address', 'Adresse'),
        TextField::new('city', 'Ville'),
        CountryField::new('country', 'Pays'),
    ];
    }
    
}
