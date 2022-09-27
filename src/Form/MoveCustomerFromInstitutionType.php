<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoveCustomerFromInstitutionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('institutions', InstitutionSelectionType::class)
            ->add('withBYOD', CheckboxType::class, array(
                'required' => false,
                'label' => 'Met BYOD toestel'
            ))
            ->add('withExtra', CheckboxType::class, array(
                'required' => false,
                'label' => 'Met Niet BYOD toestel'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
