<?php

namespace App\Form;

use App\Entity\ReturnedLoan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReturnLoanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('returneddate', DateType::class, array(
                'label' => 'returnedLoan.form.label.returneddate',
                'required' => true,
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
            ))
            ->add('images', FileType::class, array(
                'label' => 'returnedLoan.form.label.images',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'attr' => array(
                    'accecpt' => 'image/*'
                )
            ))
            ->add('remarks', TextareaType::class, array(
                'label' => 'returnedLoan.form.label.remarks',
                'required' => false
            ))
            ->add('reset', ResetType::class, array(
                'label' => 'returnedLoan.form.label.reset',
                
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReturnedLoan::class,
        ]);
    }
}
