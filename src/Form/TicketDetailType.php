<?php

namespace App\Form;

use App\Service\SP2ApiService;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketDetailType extends AbstractType
{
    private $api;

    public function __construct(SP2ApiService $api)
    {
        $this->api = $api;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('problem', TextType::class, array(
                'label' => "internalticket.title",
                'required' => true,
            ))
            ->add('description', CKEditorType::class, array(
                'label' => 'internalticket.description',
                'required' => true
            ))
            ->add('device', ChoiceType::class, array(
                'label' => 'internalticket.device',
                'choices' => $this->getDevicesForSchool($options['synergy']),
                'choice_label' => function ($value, $key, $index) {
                    return $key;
                },
                'choice_value' => function ($value) {
                    return $value;
                },
                'multiple' => false,
            ))
            ->add('state', ChoiceType::class, array(
                'label' => 'internalticket.state.state',
                'choices' => array(
                    'internalticket.state.open' => 'open',
                    'internalticket.state.closed' => 'closed',
                    'internalticket.state.inprogress' => 'in progress'
                )
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'extradevice.form.label.save',
                'attr' => array(
                    'class' => "btn btn-info"
                )
            ))
            ->add('reset', ResetType::class, array(
                'label' => "extradevice.form.label.reset",
                'attr' => array(
                    'class' => "btn btn-warning"
                )
            ));;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'synergy' => null
        ]);
    }

    private function getDevicesForSchool($school)
    {
        $ret = array();
        $data = $this->api->getDevicesForInstitution($school, false);
        foreach ($data[0] as $item) {
            $string = $item['serialnumber'];
            $ret[$string] = $item['id'];
        }
        return $ret;
    }
}
