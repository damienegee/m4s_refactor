<?php

namespace App\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('problem', TextType::class, array(
                'label' => "internalticket.problem",
                'required' => true,
            ))
            ->add('description', CKEditorType::class, array(
                'label' => 'internalticket.descritpion',
                'required' => true
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'extradevice.form.label.save',
                'attr' => array(
                    'class' => "btn btn-info"
                )
            ))
            ->add('reset', ResetType::class, array(
                'label' => 'extradevice.form.label.reset',
                'attr' => array(
                    'class' => "btn btn-warning"
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
