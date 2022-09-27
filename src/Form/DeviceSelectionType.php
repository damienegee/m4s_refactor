<?php

namespace App\Form;

use App\Service\SP2ApiService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeviceSelectionType extends AbstractType
{
    private $api;

    public function __construct(SP2ApiService $api)
    {
        $this->api = $api;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('device', ChoiceType::class, array(
                'required' => true,
                'label' => 'inventory.devices.selectmodal.title',
                'choices' => $this->getDevicesForLocation($options['schoollocationId']),
                'choice_label' => function ($value, $key, $index) {
                    return $key;
                },
                'choice_value' => function ($value) {
                    return $value;
                },
                'multiple' => false,
            )) // choicetype from api
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'schoollocationId' => null
        ]);
    }

    private function getDevicesForLocation($locationId)
    {
        $ret = array();
        $data = $this->api->getDevicesForInstitutionLocation($locationId);
        foreach ($data as $item) {
            if ($item['customer_id'] == NULL) {
                $string = $item['label'] . " " . $item['model'] . " " . $item['serialnumber'];
                $ret[$string] = $item['id'];
            }
        }
        return $ret;
    }
}
