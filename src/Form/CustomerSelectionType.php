<?php

namespace App\Form;

use App\Service\SP2ApiService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerSelectionType extends AbstractType
{
    private $api;

    public function __construct(SP2ApiService $api)
    {
        $this->api = $api;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', ChoiceType::class, array(
                'required' => true,
                'label' => 'customer.modal.title',
                'choices' => $this->getCustomersForLocation($options['schoollocationId']),
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
