<?php

namespace App\Utils;

use App\Entity\Institution;

class Utilities
{

	public static function sortInstitutionByName(Institution $a, Institution $b)
	{
		return strcmp($a->getInstitutionName(), $b->getInstitutionName());
	}

	public static function detectDelimiter(string $pathToCsv): ?string
	{
		$delimiters = array(
			';' => 0,
			',' => 0,
			'|' => 0,
		);

		$handle = fopen($pathToCsv, 'r');
		$firstline = fgets($handle);
		fclose($handle);

		foreach ($delimiters as $delimiterCharacter => $delimiterCount) {
			$foundColumnsWithThisDelimiter = count(str_getcsv($firstline, $delimiterCharacter));
			if ($foundColumnsWithThisDelimiter > 1) {
				$delimiters[$delimiterCharacter] = $foundColumnsWithThisDelimiter;
			} else {
				unset($delimiters[$delimiterCharacter]);
			}
		}

		if (!empty($delimiters)) {
			return array_search(max($delimiters), $delimiters);
		} else {
			throw new \Exception('The CSV delimiter could not been found. Should be semicolon, comma or pipe!');
		}
	}

	// public static function localeList(): array
	// {
	// 	$ret = array();
	// 	$array = (explode(',', $this->container->getParameter('app.enabledlang')));
	// 	foreach ($array as $lang) {
	// 		$ret[$lang] = $lang;
	// 	}
	// 	return $ret;
	// }
}
