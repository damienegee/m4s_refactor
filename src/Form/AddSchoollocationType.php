<?php

namespace App\Form;

use App\Entity\InstitutionLocation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddSchoollocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('institutionnumber', TextType::class, array(
                'required' => true,
                'label' => 'institutionLocation.form.label.institutionNumber'
            ))
            ->add('institutionname', TextType::class, array(
                'required' => true,
                'label' => 'institutionLocation.form.label.institutionName'
            ))
            ->add('address', AddressType::class, array(
                'label' => 'Adres'
            ))
            ->add('reset', ResetType::class, array(
                'label' => 'institutionLocation.form.label.reset',

            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InstitutionLocation::class,
        ]);
    }
}
