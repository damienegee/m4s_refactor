<?php

namespace App\Twig;

use App\Repository\InstitutionRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class InstitutionNameExtension extends AbstractExtension {
    private $ir;

    public function __construct(InstitutionRepository $ir)
    {
        $this->ir = $ir;
    }

    public function getFilters(): array
    {
        return array(
            new TwigFilter('institutionName', array($this, 'institutionName'))
        );
    }

    public function institutionName($value) {
        // $customer = $this->api->getCustomerForId($value);
        $institution = $this->ir->find($value);
        return $institution->getInstitutionName();
        // return $customer[0]['firstname'] . ' ' . $customer[0]['lastname'];
    }
}