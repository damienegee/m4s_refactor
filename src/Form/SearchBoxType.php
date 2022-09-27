<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchBoxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('searchcriteria', TextType::class, array(
                'label' => 'base.filtration.search',
                'required' => true,
                'attr' => array(
                    'placeholder' => 'base.filtration.search'
                )
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'base.filtration.search',
                'attr' => array(
                    'class' => 'btn btn-primary'
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
