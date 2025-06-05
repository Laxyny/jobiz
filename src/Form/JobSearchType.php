<?php

namespace App\Form;

use App\Entity\JobCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => JobCategory::class,
                'choice_label' => 'name',
                'required' => false,
                'label' => 'Catégorie',
                'placeholder' => 'Toutes les catégories',
            ])
            ->add('salary_min', IntegerType::class, [
                'required' => false,
                'label' => 'Salaire minimum',
                'attr' => [
                    'placeholder' => 'Ex: 30000'
                ]
            ])
            ->add('salary_max', IntegerType::class, [
                'required' => false,
                'label' => 'Salaire maximum',
                'attr' => [
                    'placeholder' => 'Ex: 60000'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}