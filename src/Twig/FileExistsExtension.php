<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FileExistsExtension extends AbstractExtension
{
	/**
	 * Return the function registered as twig extension
	 *
	 * @return array
	 */
	public function getFunctions()
	{
		return array(
			new TwigFunction('common_file_exists', array($this, 'common_file_exists'))
		);
	}

	public function common_file_exists($value)
	{
		return file_exists($value);
	}
}
