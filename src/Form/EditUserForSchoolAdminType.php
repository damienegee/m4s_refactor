<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserForSchoolAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", TextType::class, array(
                'attr' => array(
                    'readonly' => true,
                ),
            ))
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'multiple' => true,
                'expanded' => true,
                'choices' => array(
                    'User' => 'ROLE_USER',
                    'SchoolAdmin' => 'ROLE_SCHOOLADMIN'
                ),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
