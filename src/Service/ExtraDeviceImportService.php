<?php

namespace App\Service;

use App\Entity\ExtraDevice;
use App\Utils\Utilities;
use League\Csv\Reader;
use League\Csv\Statement;

class ExtraDeviceImportService
{

	private $errors = array();
	private $locationId;
	private $api;

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
			$checked = $this->api->getM4SExtraDevicesSerial($row['serialnumber']);
			if (empty($checked)) {
				$ex = new ExtraDevice();
				$ex->setProductnumber($row['productnumber']);
				$ex->setManufacturer($row['manufacturer']);
				$ex->setModel($row['model']);
				$ex->setSupplier($row['supplier']);
				$ex->setM4sSchoollocationId($this->locationId);
				$ex->setSerialNumber($row['serialnumber']);

				$inserted = $this->api->addExtraDeviceForSchoollocation($this->locationId, $ex->toJSON());

				if (!empty($inserted)) {
					if (is_array($inserted)) {
						array_push($this->errors, $row['serialnumber'] . " ==> Inserted: " . $inserted[0]);
					} else {
						array_push($this->errors, $row['serialnumber'] . " ==> " . $inserted);
					}
				}
			} else {
				array_push($this->errors, $row['serialnumber'] . " ==> Already exists in the M4S_EXTRADEVICE table");
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
