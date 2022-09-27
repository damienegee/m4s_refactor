<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FreeFieldsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('model', ChoiceType::class, array(
                'required' => true,
                'label' => 'Wat',
                'choices' => array(
                    "freefield.device" => "m4s_devices",
                    "freefield.user" => "m4s_customer"
                ),
                'multiple' => false,
            ))
            ->add('name', TextType::class, array(
                'required' => true,
                'label' => 'freefield.name'
            ))
            ->add('defaultvalue', TextType::class, array(
                'required' => false,
                'label' => 'freefield.defaultvalue'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
