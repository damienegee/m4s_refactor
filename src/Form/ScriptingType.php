<?php

namespace App\Form;

use App\Entity\Scripting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScriptingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'required' => true,
                'label' => 'Name'
            ))
            ->add('description', TextareaType::class, array(
                'required' => false,
                'label' => "Description"
            ))
            ->add('code', TextareaType::class, array(
                'required' => false,
                'label' => "Code"
            ))
            ->add('path', FileType::class, array(
                'required' => true,
                'label' => 'File',
                'attr' => array(
                    'accept' => '*/*'
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Scripting::class,
        ]);
    }
}
