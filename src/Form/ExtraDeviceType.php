<?php

namespace App\Form;

use App\Entity\ExtraDevice;
use App\Service\SP2ApiService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExtraDeviceType extends AbstractType
{
    private $api;

    public function __construct(SP2ApiService $api)
    {
        $this->api = $api;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('customer_id', ChoiceType::class, array(
            //     'required' => true,
            //     'label' => 'extradevice.form.label.m4sCustomer',
            //     'choices' => $this->getCustomersForLocation($options['schoollocationId']),
            //     'choice_label' => function ($value, $key, $index) {
            //         return $key;
            //     },
            //     'choice_value' => function ($value) {
            //         return $value;
            //     },
            //     'multiple' => false,
            // ))
            ->add('m4sSchoollocationId', HiddenType::class)
            ->add('productnumber', TextType::class, array(
                "label" => "extradevice.form.label.productnumber"
            ))
            ->add('manufacturer', TextType::class, array(
                "label" => "extradevice.form.label.manufacturer"
            ))
            ->add('model', TextType::class, array(
                "label" => "extradevice.form.label.model"
            ))
            ->add('supplier', TextType::class, array(
                "label" => "extradevice.form.label.supplier"
            ))
            ->add('serialNumber', TextType::class, array(
                'label' => 'extradevice.form.label.serial',
                'required' => true,
            ))
            ->add('reset', ResetType::class, array(
                'label' => 'extradevice.form.label.reset',

            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'schoollocationId' => null
        ]);
    }

    private function getCustomersForLocation($locationId)
    {
        $ret = array();
        $data = $this->api->getCustomerForInstitutionLocation($locationId);
        foreach ($data as $item) {
            $string = $item['firstname'] . " " . $item['lastname'];
            $ret[$string] = $item['id'];
        }
        return $ret;
    }
}
