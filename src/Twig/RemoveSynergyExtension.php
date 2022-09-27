<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class RemoveSynergyExtension extends AbstractExtension {

    public function getFilters(): array
    {
        return array(
            new TwigFilter('removeSynergy', array($this, 'removeSynergy'))
        );
    }

    public function removeSynergy($value) {
       $ret = preg_replace('/\[[\s\S]+?\]/', '', $value);

       return $ret;
    }
}