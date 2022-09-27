<?php

namespace App\Service;

use App\Entity\Device;
use App\Utils\Utilities;
use League\Csv\Reader;
use League\Csv\Statement;

class DeviceImportService
{

	private $api;
	private $errors = array();
	private $locationId;
	private $schoolId;

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
		// $stmt = Statement::create()->offset(0);
		$stmt = (new Statement())->offset(1);
		foreach ($stmt->process($reader, $headers) as $row) {
			// check if serial already exists in SP2
			$checked = $this->api->getM4SDevicesSerial($row['serialnumber']);
			if (empty($checked)) {
				$device = new Device();
				$device->setCustomer(null);
				$device->setProductnumber($row['productnumber']);
				$device->setManufacturer($row['manufacturer']);
				$device->setModel($row['model']);
				$device->setMotherboardCode($row['motherboard_code']);
				$device->setMotherboardValue($row['motherboard_value']);
				$device->setPanelCode($row['panel_code']);
				$device->setPanelValue($row['panel_value']);
				$device->setAdapter($row['adapter']);
				$device->setKeyboard($row['keyboard']);
				$device->setPanelAssemblyCode($row['panel_assembly_code']);
				$device->setPanelAssemblyValue($row['panel_assembly_value']);
				$device->setBattery($row['battery']);
				$device->setSsdCode($row['ssd_code']);
				$device->setSsdValue($row['ssd_value']);
				$device->setHddCode($row['hdd_code']);
				$device->setHddValue($row['hdd_value']);
				$device->setTopcover($row['topcover']);
				$device->setDisplayBezel($row['display_bezel']);
				$device->setDisplayBackplate($row['display_backplate']);
				$device->setTouchpad($row['touchpad']);
				$device->setBottomCover($row['bottom_cover']);
				$device->setMemoryCode($row['memory_code']);
				$device->setMemoryValue($row['memory_value']);
				$device->setPowerbutton($row['powerbutton']);
				$device->setWifiAdapter($row['wifi_adapter']);
				$device->setWifiAntenna($row['wifi_antenna']);
				$device->setLcdCable($row['lcd_cable']);
				$device->setHinges($row['hinges']);
				$device->setWebcam($row['webcam']);
				$device->setSpeakers($row['speakers']);
				$device->setDcIn($row['dc_in']);
				$device->setCablekit($row['cablekit']);
				$device->setDvddrive($row['dvddrive']);
				$device->setUsbAudioboard($row['usb_audioboard']);
				$device->setSystemIoBoard($row['system_io_board']);
				$device->setFanHeatsink($row['fan_heatsink']);
				$device->setBottomDoor($row['bottom_door']);
				$device->setMisc($row['misc']);
				$device->setHostname($row['hostname']);
				$device->setLabel($row['label']);
				$device->setSerialnumber($row['serialnumber']);
				$device->setMac1($row['mac1']);
				$device->setMac2($row['mac2']);
				$device->setProductCode($row['product_code']);
				$device->setServicemodelId($row['servicemodel_id']);
				$device->setServicemodelOrder($row['servicemodel_order']);
				$device->setEndoflife(($row['endoflife']) ? $row['endoflife'] : NULL);
				$device->setWarranty(($row['warranty']) ? $row['warranty'] : NULL);
				$device->setIntOrderId($row['int_order_id']);
				$device->setExtOrderTime($row['ext_ordertime']);
				$device->setExtDeliveryTime($row['ext_deliverytime']);

				$inserted = $this->api->addDeviceForSchoollocation($this->locationId, $device->toJSON());

				if (!empty($inserted)) {
					if (array_key_exists("error", $inserted)) {
						array_push($this->errors, $row['serialnumber'] . " ==> " . $inserted['error']);
					} else {
						if (is_array($inserted)) {
							array_push($this->errors, $row['serialnumber'] . " ==> Inserted: " . $inserted[0]);
						} else {
							array_push($this->errors, $row['serialnumber'] . " ==> " . $inserted);
						}
					}
				}
			} else {
				array_push($this->errors, $row['serialnumber'] . " ==> Already exists in the M4S_DEVICE table");
			}
		}
	}

	public function setLocationId($locationId)
	{
		$this->locationId = $locationId;
	}

	public function setSchoolId($schoolId)
	{
		$this->schoolId = $schoolId;
	}

	public function getAllErrors()
	{
		return $this->errors;
	}
}
