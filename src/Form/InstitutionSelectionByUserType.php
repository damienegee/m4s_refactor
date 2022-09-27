<?php

namespace App\Form;

use App\Entity\Institution;
use App\Entity\User;
use App\Repository\InstitutionRepository;
use App\Repository\UserRepository;
use App\Service\SP2ApiService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class InstitutionSelectionByUserType extends AbstractType
{
    private $ur;
    private $ir;
    private $cache;

    public function __construct(UserRepository $ur, InstitutionRepository $ir, CacheInterface $cache)
    {
        $this->ur = $ur;
        $this->ir = $ir;
        $this->cache = $cache;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $list =  $this->listInstitutions($options['user']);
        $builder
            ->add('institutions', ChoiceType::class, array(
                'required' => false,
                'choices' => $list,
                'choice_label' => function (Institution $institution) {
                    return $institution->getInstitutionName();
                },
                'choice_value' => function (?Institution $institution) {
                    return $institution ? $institution->getSynergyId() : '';
                },
                'multiple' => false,
                'attr' => array(
                    'class' => 'selectpicker',
                    'data-live-search' => true,
                    'data-width' => 'auto',
                    'data-container' => 'body',
                    'dropupAuto' => false,
                    "title" => 'bulkform.institution'

                ),
                'disabled' => empty($list),
                'label' => false,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'user' => null
        ]);
    }

    private function listInstitutions($userid)
    {
        /** @var User $user */
        $user = $this->ur->find($userid);

        if (!$user) {
            throw new \Exception("User not found");
        }

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $cachKey = 'LISTM4SINSTITUTION';
            $schools = $this->cache->get($cachKey, function (ItemInterface $item) {
                $item->expiresAfter(10800);
                return $this->ir->findAll();
            });
            return $schools;
        } elseif (in_array('ROLE_SCHOOLADMIN', $user->getRoles())) {
            return $user->getInstitutions();
        } else {
            return array();
        }
    }
}
