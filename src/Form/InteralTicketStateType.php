<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InteralTicketStateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('state', ChoiceType::class, array(
                'label' => 'internalticket.state.state',
                'choices' => array(
                    'internalticket.state.open' => 'open',
                    'internalticket.state.closed' => 'closed',
                    'internalticket.state.inprogress' => 'in progress'
                )
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
