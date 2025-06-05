<?php

namespace App\Form;

use App\Entity\JobApplication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostulateTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('coverLetter', TextareaType::class, [
                'label' => 'Lettre de motivation',
                'attr' => [
                    'placeholder' => 'Expliquez pourquoi vous êtes intéressé(e) par ce poste et pourquoi vous seriez un(e) bon(ne) candidat(e)...',
                    'rows' => 8,
                    'class' => 'w-full bg-teal-600 bg-opacity-40 rounded border border-slate-600 focus:border-secondary focus:ring-2 focus:ring-secondary focus:bg-transparent text-base outline-none text-gray-100 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out'
                ],
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JobApplication::class,
        ]);
    }
}