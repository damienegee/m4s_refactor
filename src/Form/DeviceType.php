<?php

namespace App\Form;

use App\Entity\Device;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeviceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reset', ResetType::class, array(
                'label' => 'inventory.form.label.reset',
            ));

        if ($options['freefieldtag01'] !== null) {
            $builder
                ->add('freefieldtag01', TextType::class, array(
                    "label" => $options['freefieldtag01'],
                    "required" => false
                ));
        }
        if ($options['freefieldtag02'] !== null) {
            $builder
                ->add('freefieldtag02', TextType::class, array(
                    "label" => $options['freefieldtag02'],
                    "required" => false
                ));
        }

        if ($options['freefieldtag03'] !== null) {
            $builder
                ->add('freefieldtag03', TextType::class, array(
                    "label" => $options['freefieldtag03'],
                    "required" => false
                ));
        }

        if ($options['freefieldtag04'] !== null) {
            $builder
                ->add('freefieldtag04', TextType::class, array(
                    "label" => $options['freefieldtag04'],
                    "required" => false
                ));
        }

        if ($options['freefieldtag05'] !== null) {
            $builder
                ->add('freefieldtag05', TextType::class, array(
                    "label" => $options['freefieldtag05'],
                    "required" => false
                ));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Device::class,
            'freefieldtag01' => null,
            'freefieldtag02' => null,
            'freefieldtag03' => null,
            'freefieldtag04' => null,
            'freefieldtag05' => null,
        ]);
    }
}
