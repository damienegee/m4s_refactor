<?php

namespace App\Form;

use App\Entity\InstitutionLocation;
use App\Service\SP2ApiService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InstitutionLocationSelectionType extends AbstractType
{
    private $api;

    public function __construct(SP2ApiService $api)
    {
        $this->api = $api;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'locations',
                ChoiceType::class,
                array(
                    'required' => false,
                    'choices' => $this->listInstitutionLocation($options['synergy']),
                    'choice_label' => function (InstitutionLocation $location) {
                        return $location->getInstitutionName();
                    },
                    'choice_value' => function (?InstitutionLocation $location) {
                        return $location ? $location->getId() : '';
                    },
                    'multiple' => false,
                    'attr' => array(
                        'class' => 'selectpicker',
                        'data-live-search' => true,
                        "data-width" => "auto",
                        "data-container" => "body",
                        "title" => 'bulkform.location'
                    ),
                    'label' => false
                )

            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'synergy' => null
        ]);
    }

    private function listInstitutionLocation($synergy)
    {
        $ret = array();
        $locations = $this->api->getSchoollocationsForSchool($synergy);
        foreach ($locations as $location) {
            $loc = new InstitutionLocation();
            $loc->setInstitutionname($location['institutionName']);
            $loc->setInstitutionnumber($location['institutionNumber']);
            $loc->setId($location['id']);
            array_push($ret, $loc);
        }
        return $ret;
    }
}
