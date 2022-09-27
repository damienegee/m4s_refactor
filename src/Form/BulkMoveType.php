<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BulkMoveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('locations', InstitutionLocationSelectionType::class, array(
                'synergy' => $options['synergy'],
                'label' => false,
                'attr' => array(
                    'class' => 'ml-1 mr-1'
                )
            ))
            ->add('institutions', InstitutionSelectionByUserType::class, array(
                'user' => $options['user'],
                'label' => false,
                'attr' => array(
                    'class' => 'ml-1 mr-1'
                )
            ))
            ->add(
                'submit',
                SubmitType::class,
                array(
                    'attr' => array(
                        "class" => "btn btn-info ml-1 mr-1"
                    ),
                    'label' => "bulkform.move"
                )
            );

        if ($options['usedin'] === 'customer') {
            $builder->add('type', CustomerFunctionType::class);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'synergy' => null,
            'user' => null,
            'usedin' => null,
        ]);
    }
}
