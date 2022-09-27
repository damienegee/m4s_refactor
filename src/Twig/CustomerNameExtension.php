<?php

namespace App\Twig;

use App\Service\SP2ApiService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CustomerNameExtension extends AbstractExtension
{

    private $api;

    public function __construct(SP2ApiService $api)
    {
        $this->api = $api;
    }

    public function getFilters(): array
    {
        return array(
            new TwigFilter('customername', array($this, 'customername'))
        );
    }

    public function customername($value)
    {
        $customer = $this->api->getCustomerForId($value);

        return $customer[0]['firstname'] . ' ' . $customer[0]['lastname'];
    }
}
