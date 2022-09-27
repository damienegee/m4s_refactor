<?php

namespace App\Form;

use App\Entity\Institution;
use App\Repository\InstitutionRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class InstitutionSelectionType extends AbstractType
{
    private $ir;
    private $cache;

    public function __construct(InstitutionRepository $ir, CacheInterface $cache)
    {
        $this->ir = $ir;
        $this->cache = $cache;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('institutions', ChoiceType::class, array(
                'required' => true,
                'choices' => $this->getAllInstitutions(),
                'choice_label' => function (Institution $institution) {
                    return $institution->getInstitutionName();
                },
                'choice_value' => function (?Institution $institution) {
                    return $institution ? $institution->getSynergyId() : '';
                },
                'multiple' => false,
                'attr' => array(
                    'class' => 'selectpicker',
                    'data-live-search' => true
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }

    private function getAllInstitutions()
    {
        $cachKey = 'LISTM4SINSTITUTION';
        $schools = $this->cache->get($cachKey, function (ItemInterface $item) {
            $item->expiresAfter(10800);
            return $this->ir->findAll();
        });

        return $schools;
    }
}
