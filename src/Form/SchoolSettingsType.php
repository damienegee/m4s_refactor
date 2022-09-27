<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class SchoolSettingsType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('use_school_facturation_default', CheckboxType::class, [
			'label' => 'school_settings.use_school_facturation_default',
			'required' => false,
			'attr' => array('checked' => $builder->getData()['use_school_facturation_default'])
		]);
	}
}
