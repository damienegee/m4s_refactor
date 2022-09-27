<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UTF8DecoderExtension extends AbstractExtension
{


	public function getFilters(): array
	{
		return array(
			new TwigFilter('utf8decoder', array($this, 'utf8decoder'))
		);
	}

	public function utf8decoder($value)
	{
		return utf8_encode($value);
	}
}
