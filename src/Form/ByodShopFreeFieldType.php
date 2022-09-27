<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ByodShopFreeFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
			->add('fieldTitle', TextType::class, array(
				'required' => true,
				'label' => 'byod.freefields.label'
			))
            ->add('fieldType', ChoiceType::class, array(
				'required' => true,
				'label' => 'byod.freefields.inputtype',
				'placeholder' => 'byod.freefields.inputmessage',
				'multiple' => false,
				'choices' => array(
					'byod.freefields.text' => 'text',
					'byod.freefields.number' => 'number',
				)
			))
			->add('active', CheckboxType::class, array(
				'required' => false,
				'label' => 'byod.freefields.active',
			))
			->add('required', CheckboxType::class, array(
				'required' => false,
				'label' => 'byod.freefields.required',
			));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
