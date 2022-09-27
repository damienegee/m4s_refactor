<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use App\Service\SP2ApiService;
use Twig\TwigFilter;

class GetUsedForExtension extends AbstractExtension
{
	private $api;

	public function __construct(SP2ApiService $api)
	{
		$this->api = $api;
	}

	public function getFilters(): array
	{
		return array(
			new TwigFilter('usedforlabel', array($this, 'usedforlabel'))
		);
	}

	public function usedforlabel($value)
	{
		$label = $this->api->getLabelByOrder($value);
		if (empty($label)) {
			return '';
		} else {
			return $label[0]['label'];
		}
	}
}
