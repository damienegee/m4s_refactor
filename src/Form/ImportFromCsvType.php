<?php

namespace App\Form;

use App\Service\SP2ApiService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImportFromCsvType extends AbstractType
{
    private $api;

    public function __construct(SP2ApiService $api)
    {
        $this->api = $api;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('schoollocation', ChoiceType::class, array(
                'required' => true,
                'label' => 'inventory.details.information.device.location',
                'choices' => $this->getSchoollocationForSchool($options['synergy']),
                'choice_label' => function ($value, $key, $index) {
                    return $key;
                },
                'choice_value' => function ($value) {
                    return $value;
                },
                'multiple' => false,
            ))
            ->add('file', FileType::class, array(
                'required' => true,
                'label' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [ // We want to let upload only txt, csv or Excel files
                            'text/x-comma-separated-values',
                            'text/comma-separated-values',
                            'text/x-csv',
                            'text/csv',
                            'text/plain',
                            'application/octet-stream',
                            'application/vnd.ms-excel',
                            'application/x-csv',
                            'application/csv',
                            'application/excel',
                            'application/vnd.msexcel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        ],
                        'mimeTypesMessage' => "This document isn't valid.",
                    ])
                ],
            ))
            ->add('send', SubmitType::class);

        if (isset($options['extradevice']) && $options['extradevice']) {
            $builder->add('extradevice', CheckboxType::class, array(
                'label' => 'bulkform.notsignpost',
                'required' => false
            ));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'synergy' => null,
            'extradevice' => null
        ]);
    }

    private function getSchoollocationForSchool($synergy)
    {
        $ret = array();
        $schoollocations = $this->api->getSchoollocationsForSchool($synergy);
        foreach ($schoollocations as $location) {
            $ret[$location['institutionName']] = $location['id'];
        }

        return $ret;
    }
}
