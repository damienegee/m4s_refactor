<?php

namespace App\Service;

use App\Utils\Utilities;
use League\Csv\Reader;
use League\Csv\Statement;

class AssiginDevicesToUsersService
{
	private $api;
	private $errors = array();
	private $locationId;

	public function __construct(SP2ApiService $api)
	{
		$this->api = $api;
	}

	public function execute($filedir, $filename, $notbyodDevice = false)
	{
		$delimeter = Utilities::detectDelimiter($filedir . '/' . $filename);
		$reader = Reader::createFromPath($filedir . '/' . $filename);
		$reader->setDelimiter($delimeter);
		$headers = $reader->fetchOne();
		$stmt = (new Statement())->offset(1);
		foreach ($stmt->process($reader, $headers) as $row) {
			if ($notbyodDevice) {
				$extraDevice = $this->api->getM4SExtraDevicesSerial($row['serialnumber']);
				if (empty($extraDevice)) {
					array_push($this->errors, $row['serialnumber'] . " ==> NOT FOUND");
				} else {
					$user = $this->api->getCustomerForNameAndLocation($row['firstname'], $row['lastname'], $this->locationId);
					if (empty($user)) {
						array_push($this->errors, $row['firstname'] . ' ' . $row['lastname'] . " ==> NOT FOUND FOR THE SELECTED LOCATION");
					} else {
						$this->api->updateLocationOnExtraDevice($extraDevice[0]['id'], $this->locationId);
						$this->api->updateCustomerOnExtraDevice($extraDevice[0]['id'], $user[0]['id']);
						array_push($this->errors, $row['serialnumber'] . ' ==> ' . 'is set to ' . $row['firstname'] . ' ' . $row['lastname']);
					}
				}
			} else {
				$device = $this->api->getM4SDevicesSerial($row['serialnumber']);
				if (empty($device)) {
					array_push($this->errors, $row['serialnumber'] . " ==> NOT FOUND");
				} else {
					$user = $this->api->getCustomerForNameAndLocation($row['firstname'], $row['lastname'], $this->locationId);
					if (empty($user)) {
						array_push($this->errors, $row['firstname'] . ' ' . $row['lastname'] . " ==> NOT FOUND FOR THE SELECTED LOCATION");
					} else {
						$this->api->updateLocationOnDevice($device[0]['id'], $this->locationId);
						$this->api->updateCustomerOnDevice($device[0]['id'], $user[0]['id']);
						array_push($this->errors, $row['serialnumber'] . ' ==> ' . 'is set to ' . $row['firstname'] . ' ' . $row['lastname']);
					}
				}
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
