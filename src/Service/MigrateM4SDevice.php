<?php

namespace App\Service;

// use App\Entity\Device;
// use App\Repository\CustomerRepository;
// use App\Repository\InstitutionLocationRepository;
// use Doctrine\ORM\EntityManagerInterface;
// use Doctrine\Persistence\ConnectionRegistry;
// use Doctrine\Persistence\ManagerRegistry;

/**
 * This is only to use for dev purpose. If you don't have any API you can import data locally
 */
class MigrateM4SDevice {
    private $ilr;
    private $cr;
    private $mr;
    private $em;
    private $query = "
        SELECT DISTINCT
            device.hostname as hostname,
            device.label as label,
            device.serialnumber as serialnumber,
            device.mac1 as mac1,
            device.mac2 as mac2,
            device.product_code as productCode,
            device.servicemodel_id as servicemodelId,
            device.servicemodel_order as servicemodelOrder,
            device.endoflife as endoflife,
            device.warranty as warranty,
            device.int_order_id as intOrderId,
            device.ext_ordertime as extOrdertime,
            device.ext_deliverytime as extDeliverytime,
            device.deleted as deleted,
            device.campus_id as campusId,
            device.client_id as clientId,
            product.productnumber as productnumber, 
            product.manufacturer as manufacturer, 
            product.model as model, 
            product.motherboard_code as motherboardCode, 
            product.motherboard_value as motherboardValue, 
            product.panel_code as panelCode, 
            product.panel_value as panelValue, 
            product.adapter as adapter, 
            product.keyboard as keyboard, 
            product.panel_assembly_code as panelAssemblyCode, 
            product.panel_assembly_value as panelAssemblyValue,
            product.battery as battery,
            product.ssd_code as ssdCode,
            product.ssd_value as ssdValue,
            product.hdd_code as hddCode,
            product.hdd_value as hddValue,
            product.topcover as topcover,
            product.display_bezel as displayBezel,
            product.display_backplate as displayBackplate,
            product.touchpad as touchpad,
            product.bottom_cover as bottomCover,
            product.memory_code as memoryCode,
            product.memory_value as memoryValue,
            product.powerbutton as powerbutton,
            product.wifi_adapter as wifiAdapter,
            product.wifi_antenna as wifiAntenna,
            product.lcd_cable as lcdCable,
            product.hinges as hinges,
            product.webcam as webcam,
            product.speakers as speakers,
            product.dc_in as dcIn,
            product.cablekit as cablekit,
            product.dvddrive as dvddrive,
            product.usb_audioboard as usbAudioboard,
            product.system_io_board as systemIoBoard,
            product.fan_heatsink as fanHeatsink,
            product.bottom_door as bottomDoor,
            product.misc as misc,
            product.picture_url as pictureUrl,
            campus.name as institutionName,
            campus.institution_number as institutionNumber,
            client.firstname as firstname,
            client.lastname as lastname,
            client.mail as email
        FROM device
        INNER JOIN product ON device.product_code = product.product_code
        INNER JOIN campus ON device.campus_id = campus.id
        LEFT OUTER JOIN client ON device.client_id = client.id
        WHERE device.deleted != 1
        AND product.deleted != 1
        AND campus.deleted != 1
    ";

    public function __construct()
    {
        // $this->ilr = $ilr;
        // $this->cr = $cr;
        // $this->mr = $mr;
        // $this->em = $em;
    }

    public function migrateM4SDevice() {
         /** @var ConnectionRegistry $conn */
    //      $conn = $this->mr->getManager('byod_m4s');
    //      $statement = $conn->getConnection()->prepare($this->query);
 
    //      $resultSet = $statement->executeQuery();
 
    //      $results = $resultSet->fetchAllAssociative();

    //      foreach($results as $item) {
    //          $device = new Device();

    //          if($item['clientId'] != NULL) {
    //              $customer = $this->cr->findByMailName($item['email'], $item['firstname'], $item['lastname']);
    //              $device->setCustomer($customer);
    //          } else {
    //              $device->setCustomer(NULL);
    //          }

    //          if($item['campusId'] != NULL) {
    //              $institutionLocation = null;
    //              if($item['institutionNumber'] != 0) {
    //                 $institutionLocation = $this->ilr->findByInstitutionNumber($item['instiutionNumber']);
    //              } else {
    //                 $institutionLocation = $this->ilr->findByInstitutionName($item['institutionName']);
    //              }
    //              $device->setSchoolLocation($institutionLocation);
    //          }

    //          $device->setHotname($item['hostname']);
    //          $device->setLabel($item['label']);
    //          $device->setSerialnumber($item['serialnumber']);
    //          $device->setMac1($item['mac1']);
    //          $device->setMac2($item['mac2']);
    //          $device->setProductCode($item['productCode']);
    //          $device->setServicemodelId($item['servicemodelId']);
    //          $device->setServicemodelOrder($item['servicemodelOrder']);
    //          $device->setEndoflife($item['endoflife']);
    //          $device->setWarranty($item['waranty']);
    //          $device->setIntOrderId($item['intOrderId']);
    //          $device->setExtOrderTime($item['extOrdertime']);
    //          $device->setExtDeliveryTime($item['extDeliverytime']);
    //          $device->setDeleted($item['deleted'] === 0 ? false : true);
    //          $device->setProductnumber($item['productnumber']);
    //          $device->setManufacturer($item['manufacturer']);
    //          $device->setModel($item['model']);
    //          $device->setMotherboardCode($item['motherboardCode']);
    //          $device->setMotherboardValue($item['motherboardValue']);
    //          $device->setPanelCode($item['panelCode']);
    //          $device->setPanelValue($item['panelValue']);
    //          $device->setAdapter($item['adapter']);
    //          $device->setKeyboard($item['keyboard']);
    //          $device->setPanelAssemblyCode($item['panelAssemblyCode']);
    //          $device->setPanelAssemblyValue($item['panelAssemblyValue']);
    //          $device->setBattery($item['battery']);
    //          $device->setSsdCode($item['ssdCode']);
    //          $device->setSsdValue($item['ssdValue']);
    //          $device->setHddCode($item['hddCode']);
    //          $device->setHddValue($item['hddValue']);
    //          $device->setTopcover($item['topcover']);
    //          $device->setDisplayBezel($item['displayBezel']);
    //          $device->setDisplayBackplate($item['displayBackplate']);
    //          $device->setTouchpad($item['touchpad']);
    //          $device->setBottomCover($item['bottomCover']);
    //          $device->setMemoryCode($item['memoryCode']);
    //          $device->setMemoryValue($item['memoryValue']);
    //          $device->setPowerbutton($item['powerbutton']);
    //          $device->setWifiAdapter($item['wifiAdapter']);
    //          $device->setWifiAntenna($item['wifiAntenna']);
    //          $device->setLcdCable($item['lcdCable']);
    //          $device->setHinges($item['hinges']);
    //          $device->setWebcam($item['webcam']);
    //          $device->setSpeakers($item['speakers']);
    //          $device->setDcIn($item['dcIn']);
    //          $device->setCablekit($item['cablekit']);
    //          $device->setDvddrive($item['dvddrive']);
    //          $device->setUsbAudioboard($item['usbAudioboard']);
    //          $device->setSystemIoBoard($item['systemIoBoard']);
    //          $device->setFanHeatsink($item['fanHeadsink']);
    //          $device->setBottomDoor($item['bottomDoor']);
    //          $device->setMisc($item['misc']);
    //          $device->setPicture($item['pictureUrl']);
             
    //          $this->em->persist($device);
    //          $this->em->flush();
    //      }
    }
}