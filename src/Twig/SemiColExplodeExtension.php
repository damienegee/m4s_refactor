<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SemiColExplodeExtension extends AbstractExtension
{

	public function getFunctions()
	{
		return array(
			new TwigFunction('explodeSemiCol', [$this, 'explodeSemiCol'])
		);
	}

	public function explodeSemiCol($value)
	{
		return (explode(';', $value));
	}
}
