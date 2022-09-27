<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Enum\CustomerType as EnumCustomerType;

class CustomerFunctionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, array(
                'choices' => array(
                    EnumCustomerType::EMPLOYEE => EnumCustomerType::EMPLOYEE,
                    EnumCustomerType::ADMINISTRATION => EnumCustomerType::ADMINISTRATION,
                    EnumCustomerType::STUDENT => EnumCustomerType::STUDENT,
                    EnumCustomerType::SALES => EnumCustomerType::SALES,
                    EnumCustomerType::TEACHER => EnumCustomerType::TEACHER,
                    EnumCustomerType::ICT_COORDINATOR => EnumCustomerType::ICT_COORDINATOR,
                ),
                'label' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'selectpicker',
                    "data-width" => "auto",
                    "data-container" => "body",
                    "title" => 'customer.form.type'
                ),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
