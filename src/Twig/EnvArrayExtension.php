<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EnvArrayExtension extends AbstractExtension
{

	public function getFunctions()
	{
		return array(
			new TwigFunction('explodeEnv', [$this, 'explodeEnv'])
		);
	}

	public function explodeEnv($value)
	{
		return (explode(',', $value));
	}
}
