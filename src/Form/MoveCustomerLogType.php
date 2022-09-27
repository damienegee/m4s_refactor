<?php

namespace App\Form;

use App\Entity\MoveCustomerLog;
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

class MoveCustomerLogType extends AbstractType
{
    private $api;

    public function __construct(SP2ApiService $api)
    {
        $this->api = $api;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customerId',  NumberType::class, array(
                'label' => 'movecustomer.modal.form.customer',
                'attr' => array(
                    'readonly' => true
                )
            ))
            ->add('customerName', TextType::class, array(
                'label' => 'movecustomer.modal.form.customerName',
                'attr' => array(
                    'readonly' => true
                )
            ))
            ->add('fromLocationId', HiddenType::class)
            ->add('fromLocationName', TextType::class, array(
                'label' => 'movecustomer.modal.form.fromlocation',
                'attr' => array(
                    'readonly' => true,
                )
            ))
            ->add('toLocationId', HiddenType::class)
            ->add('toLocationName', ChoiceType::class, array(
                'required' => true,
                'label' => 'movecustomer.modal.form.tolocation',
                'choices' => $this->getSchoollocationsForSchoolId($options['school_id']),
                'choice_value' => function ($value) {
                    return $value;
                },
                'choice_label' => function ($value, $key, $item) {
                    return $key;
                }
            ))
            ->add('whenMoved', DateType::class, array(
                'label' => 'movecustomer.modal.form.when',
                'required' => true,
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
            ))
            ->add('moveDevice', CheckboxType::class, array(
                "label" => 'Toestel mee verhuizen?',
                "required" => false
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'school_id' => null
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
