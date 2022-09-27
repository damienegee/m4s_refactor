<?php

namespace App\Form;

use App\Entity\MoveDeviceLog;
use App\Service\SP2ApiService;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoveDeviceLogType extends AbstractType
{
    private $api;

    public function __construct(SP2ApiService $api)
    {
        $this->api = $api;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('deviceId', NumberType::class, array(
                'label' => 'movedevice.modal.form.device',
                'attr' => array(
                    'readonly' => true
                )
            ))
            ->add('deviceSerial', TextType::class, array(
                'label' => 'movedevice.modal.form.serial',
                'attr' => array(
                    'readonly' => true
                )
            ))
            ->add('fromLocationId', HiddenType::class)
            ->add('fromLocationName', TextType::class, array(
                'label' => 'movedevice.modal.form.fromlocation',
                'attr' => array(
                    'readonly' => true,
                )
            ))
            ->add('toLocationId', HiddenType::class)
            ->add('toLocationName', ChoiceType::class, array(
                'label' => 'movedevice.modal.form.tolocation',
                'choices' => $this->getSchoollocationsForSchoolId($options['school_id']),
                'choice_value' => function ($value) {
                    return $value;
                },
                'choice_label' => function ($value, $key, $item) {
                    return $key;
                }
            ))
            ->add('whenMoved', DateType::class, array(
                'label' => 'movedevice.modal.form.when',
                'required' => true,
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
            ))
           ;

        if ($options['customer_id'] !== null) {
        	$builder
				->add('moveCustomer', CheckboxType::class, array(
					"label" => 'Gebruiker mee verhuizen?',
					"required" => false
				))
			;
		}
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'school_id' => null,
			'customer_id' => null
        ]);
    }

    private function getSchoollocationsForSchoolId($schoolId)
    {
        $ret = array();
        $data = $this->api->getSchoollocationsForSchoolId($schoolId);
        foreach ($data as $item) {
            $ret[$item['institutionName']] = $item['id'];
        }
        return $ret;
    }
}
