<?php

namespace App\Form;

use App\Entity\Loan;

use App\Service\SP2ApiService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoanType extends AbstractType
{

    private $api;

    public function __construct(SP2ApiService $api)
    {
        $this->api = $api;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startdate', DateType::class, array(
                'label' => 'loan.form.startdate',
                'required' => true,
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
            ))
            ->add('user', ChoiceType::class, array(
                'required' => true,
                'label' => 'loan.form.user',
                'choices' => $this->getClientsBySchoolId($options['location_id']),
                'choice_label' => function ($value, $key, $index) {
                    return $key;
                },
                'choice_value' => function ($value) {
                    return $value;
                },
                'multiple' => false,
            )) // choicetype from api
            ->add('deviceSerial', TextType::class, array(
                'required' => false,
                'label' => 'loan.form.device',
                'attr' => array(
                    'readonly' => true
                )
            )) // choicetype from api
            ->add('enddate', DateType::class, array(
                'label' => 'loan.form.enddate',
                'required' => false,
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
            ))
            ->add('images', FileType::class, array(
                'label' => 'loan.form.images',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'attr' => array(
                    'accept' => 'image/*'
                )
            ))
            ->add('remark', TextareaType::class, array(
                'label' => 'loan.form.remark',
                'required' => false
            ))
            ->add('signature', HiddenType::class)
            ->add('reset', ResetType::class, array(
                'label' => 'loan.form.reset',

            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Loan::class,
            'location_id' => null
        ]);
    }

    private function getClientsBySchoolId($locationId)
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
