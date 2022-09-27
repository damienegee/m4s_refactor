<?php

namespace App\Form;

use App\Entity\ReleaseNotes;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReleaseNotesType extends AbstractType
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $date = new DateTime();
        $builder
            ->add('version', TextType::class, array(
                "label" => "Versie",
                "required" => true,
            ))

            ->add('created', DateTimeType::class, array(
                'attr' => array(
                    'value' => $date->format('d-m-Y H:M:S'),
                    'hidden' => true
                ),
                "label_attr" => array(
                    'hidden' => true
                )
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Submit',
                'attr' => array(
                    'class' => "btn btn-success",
                )
            ));

        foreach ($this->localeList() as $lang) {
            $builder->add('title_' . $lang, TextType::class, array(
                "label" => "Titel " . strtoupper($lang),
                "required" => true,
            ))
                ->add('description_' . $lang, CKEditorType::class, array(
                    "label" => "Description " . $lang
                ));
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            //'data_class' => ReleaseNotes::class,
        ]);
    }

    public function localeList(): array
    {
        $ret = array();
        $array = (explode(',', $this->container->getParameter('app.enabledlang')));
        foreach ($array as $lang) {
            $ret[$lang] = $lang;
        }
        return $ret;
    }
}
