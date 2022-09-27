<?php

namespace App\Form;

use App\Service\SP2ApiService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeviceImageType extends AbstractType
{
    private $api;

    public function __construct(SP2ApiService $api)
    {
        $this->api = $api;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('devicemodel', ChoiceType::class, array(
                'required' => true,
                'label' => 'Model',
                'choices' => $this->getDeviceModel(),
                'choice_label' => function ($value, $key, $index) {
                    return $value;
                },
                'choice_value' => function ($value) {
                    return $value;
                },
                'multiple' => false,
            ))
            ->add('image', FileType::class, array(
                'required' => true,
                'label' => 'Image',
                'attr' => array(
                    'accept' => 'image/*'
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }

    private function getDeviceModel()
    {
        $ret =  array();
        $models = $this->api->getM4SDevicesModel();
        foreach ($models as $model) {
            array_push($ret, $model['model']);
        }
        return $ret;
    }
}
