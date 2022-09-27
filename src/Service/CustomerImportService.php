<?php

namespace App\Service;

use App\Entity\Customer;
use App\Utils\Utilities;
use League\Csv\Reader;
use League\Csv\Statement;

class CustomerImportService
{
	private $api;
	private $errors = array();
	private $locationId;

	public function __construct(SP2ApiService $api)
	{
		$this->api = $api;
	}

	public function execute($filedir, $filename)
	{
		$delimeter = Utilities::detectDelimiter($filedir . '/' . $filename);
		$reader = Reader::createFromPath($filedir . '/' . $filename);

		$reader->setDelimiter($delimeter);
		$reader->setOutputBOM(Reader::BOM_UTF8);
		$headers = $reader->fetchOne();
		$stmt = (new Statement())->offset(1);
		foreach ($stmt->process($reader, $headers) as $row) {
			//$checked = $this->api->getM
			$checked = $this->api->getCustomerForNameAndEmail($row['firstname'], $row['lastname'], $row['email']);
			if (empty($checked)) {
				$customer = new Customer();
				$customer->setFirstname(($row['firstname']));
				$customer->setLastname(($row['lastname']));
				$customer->setEmail(trim($row['email']));
				$customer->setType(trim(strtoupper($row['type'])));

				$inserted = $this->api->addCustomerForSchoollocation($this->locationId, $customer->toJSON());
				// dd($inserted);
				if (!empty($inserted)) {
					if (array_key_exists("error", $inserted)) {
						array_push($this->errors, $row['email'] . " ==> " . $inserted['error']);
					} else {
						if (is_array($inserted)) {
							array_push($this->errors, $row['email'] . " ==> Inserted: " . $inserted[0]);
						} else {
							array_push($this->errors, $row['email'] . " ==> " . $inserted);
						}
					}
				}
			} else {
				array_push($this->errors, $row['email'] . " ==> Already exists in the M4S_CUSTOMER table");
			}
		}
	}

	public function setLocationId($locationId)
	{
		$this->locationId = $locationId;
	}

	public function getAllErrors()
	{
		return $this->errors;
	}
}
