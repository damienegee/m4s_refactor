<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('street', TextType::class, array(
                'required' => true,
                'label' => 'institutionLocation.form.address.label.street'
            ))
            ->add('number', TextType::class, array(
                'required' => true,
                'label' => 'institutionLocation.form.address.label.number',
                'empty_data' => 'z.n.'
            ))
            ->add('bus', TextType::class, array(
                'required' => false,
                'label' => 'institutionLocation.form.address.label.bus'
            ))
            ->add('zipcode', NumberType::class, array(
                'required' => true,
                'label' => 'institutionLocation.form.address.label.zipcode',
                'attr' => array(
                    'minlength' => 4,
                    'maxlength' => 4
                )
            ))
            ->add('city', TextType::class, array(
                'required' => true,
                'label' => 'institutionLocation.form.address.label.city'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
