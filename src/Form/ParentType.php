<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname', TextType::class, array(
                'required' => true,
                'label' => 'customer.form.lastname'
            ))
            ->add('firstname', TextType::class, array(
                'required' => true,
                'label' => 'customer.form.firstname'
            ))
            ->add('email', EmailType::class, array(
                'required' => true,
                'label' => 'customer.form.email'
            ))
            ->add('phone', TelType::class, array(
                'required' => false,
                'label' => 'Phone'
            ))
            ->add('reset', ResetType::class, array(
                'label' => 'customer.form.reset',

            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
