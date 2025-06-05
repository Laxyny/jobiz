<?php

namespace App\Controller\Admin;

use App\Entity\Job;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class JobCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Job::class;
    }

    public function configureFields(string $pageName): iterable
{
    return [
        IdField::new('id', 'ID')->hideOnForm(),
        TextField::new('title', 'Titre'),
        TextareaField::new('description', 'Description'),
        CountryField::new('country', 'Pays'),
        TextField::new('city', 'Ville'),
        BooleanField::new('remote_allowed', 'Télétravail autorisé'),
        IntegerField::new('salary_range_min', 'Salaire min'),
        NumberField::new('salary_range_max', 'Salaire max'),
        AssociationField::new('jobtype', 'Type de poste'),
        AssociationField::new('jobcategorys', 'Catégories')->setFormTypeOption('by_reference', false),
        AssociationField::new('company', 'Entreprise'),
        AssociationField::new('jobApplications', 'Candidatures')->hideOnForm(),
    ];
}
}
