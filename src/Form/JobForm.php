<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Job;
use App\Entity\JobCategory;
use App\Entity\JobType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CountryType;


class JobForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => 'Titre du poste',
            ])
            ->add('description', null, [
                'label' => 'Description',
            ])
            ->add('country', CountryType::class, [
                'label' => 'Pays',
                'placeholder' => 'Sélectionnez un pays',
            ])
            ->add('city', null, [
                'label' => 'Ville',
            ])
            ->add('remote_allowed', null, [
                'label' => 'Télétravail autorisé',
            ])
            ->add('salary_range_min', null, [
                'label' => 'Salaire minimum',
            ])
            ->add('salary_range_max', null, [
                'label' => 'Salaire maximum',
            ])
            ->add('jobtype', EntityType::class, [
                'class' => JobType::class,
                'choice_label' => 'name',
                'label' => 'Type de poste',
            ])
            ->add('jobcategorys', EntityType::class, [
                'class' => JobCategory::class,
                'choice_label' => 'name',
                'multiple' => true,
                'label' => 'Catégories',
            ])
            ->add('company', EntityType::class, [
                'class' => Company::class,
                'choice_label' => 'name',
                'label' => 'Entreprise',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
        ]);
    }
}
