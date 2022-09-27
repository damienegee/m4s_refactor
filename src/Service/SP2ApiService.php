<?php

namespace App\Service;

use App\Entity\User;
use App\Utils\Crypt;
use Symfony\Component\DependencyInjection\ContainerInterface;
// use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use DateTime;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SP2ApiService
{

    private $client;
    private $crypt;
    private $container;
    private $isDev;
    private $token;

    public function __construct(Crypt $crypt, ContainerInterface $container, HttpClientInterface $client, ParameterBagInterface $appParams, TokenStorageInterface $token)
    {
        $this->client = $client;
        $this->crypt = $crypt;
        $this->container = $container;
        $this->isDev = $appParams->get('app.env') === 'dev';
        $this->token = $token;
    }

    public function getSchoolForSynergy($synergy)
    {
        $api_url = 'getSchoolForSynergy.php';

        $postQueryArray = array(
            "synergy_id" => $synergy,
            "date" => (new DateTime())->format('U'),
        );
        return  $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getAGSOWebshopOrders($sortField, $sortDirection)
    {
        $api_url = 'getWebshopOrdersForStoreId.php';

        $postQueryArray = array(
            "store_id" => strval(41),
            "date" => (new DateTime())->format('U')
        );
        if ($sortField != null && $sortDirection != null) {
            $postQueryArray['sort_field'] = $sortField;
            $postQueryArray['sort_direction'] = $sortDirection;
        }

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getDashboardCounterForSchool($synergyId, $counter)
    {
        $result = array();
        $api_url = 'getDashboardCounterForSchool.php';

        $postQueryArray = array(
            "synergy_id" => $synergyId,
            "counter" => $counter,
            "date" => (new DateTime())->format('U'),
        );
        $data = $this->getApiRequest($api_url, $postQueryArray);
        foreach ($data as $item) {
            $result[] = $item;
        }
        return $result;
    }

    public function getDashboardCounterForSchoolLocation($locationId, $counter)
    {
        $result = array();
        $api_url = 'getDashboardCounterForSchoollocation.php';

        $postQueryArray = array(
            "location_id" => $locationId,
            "counter" => $counter,
            "date" => (new DateTime())->format('U'),
        );
        $data = $this->getApiRequest($api_url, $postQueryArray);
        foreach ($data as $item) {
            $result[] = $item;
        }
        return $result;
    }

    public function getSchoollocationsForSchool($synergyId)
    {

        $api_url = 'getSchoollocationsForSchool.php';

        $postQueryArray = array(
            "synergy_id" => $synergyId,
            "date" => (new DateTime())->format('U'),
        );
        $data = $this->getApiRequest($api_url, $postQueryArray);

        return $data;
    }

    public function getSchoollocationsForSchoolId($schoolId)
    {
        $api_url = 'getSchoollocationsForSchoolId.php';
        $postQueryArray = array(
            "school_id" => $schoolId,
            "date" => (new DateTime())->format('U'),
        );
        $data = $this->getApiRequest($api_url, $postQueryArray);

        return $data;
    }

    public function getSchoollocation($locationId)
    {
        $api_url = 'getSchoolLocationForId.php';

        $postQueryArray = array(
            "location_id" => $locationId,
            "date" => (new DateTime())->format('U'),
        );
        $data = $this->getApiRequest($api_url, $postQueryArray);

        return $data;
    }

    public function getDevicesForInstitution($synergyId, $nolocation)
    {
        $api_url = 'getM4SDevicesBySchoolId.php';

        $postQueryArray = array(
            "synergy_id" => $synergyId,
            'nolocation' => $nolocation,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getCustomerForInstitution($synergyId, $nolocation)
    {
        $api_url = 'getM4SClientsForSchoolId.php';

        $postQueryArray = array(
            "synergy_id" => $synergyId,
            "nolocation" => $nolocation,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getDevicesForInstitutionLocation($locationId)
    {
        $api_url = 'getM4SDevicesBySchoollocationId.php';

        $postQueryArray = array(
            "location_id" => $locationId,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getExtraDevicesForInstitutionLocation($locationId)
    {
        $api_url = 'getM4SExtraDevicesBySchoollocationId.php';

        $postQueryArray = array(
            "location_id" => $locationId,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getCustomerForInstitutionLocation($locationId)
    {
        $api_url = 'getM4SCustomersBySchoollocationId.php';

        $postQueryArray = array(
            "location_id" => $locationId,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getFieldServicesForInstitution($synergyId)
    {
        $api_url = 'getFieldServices.php';
        /** @var User $user */
        $user = $this->token->getToken()->getUser();
        $postQueryArray = array(
            "synergy_id" => strval($synergyId),
            "lang" => $user->getLocale(),
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getFieldServicesByLocation($locationId)
    {
        $api_url = 'getFieldServicesByLocation.php';

        $postQueryArray = array(
            "location_id" => strval($locationId),
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function addSchoollocationForSchool($synergyId, $locData)
    {
        $api_url = 'addSchoollocationToSchool.php';
        $postQueryArray = array(
            "synergy_id" => strval($synergyId),
            "location_data" => $locData,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function addCustomerForSchoollocation($locid, $cusData)
    {
        $api_url = 'addCustomerToSchoollocation.php';

        $postQueryArray = array(
            "location_id" => $locid,
            "customer_data" => $cusData,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function addDeviceForSchoollocation($locid, $devData)
    {
        $api_url = "addDeviceToSchoollocation.php";

        $postQueryArray = array(
            "location_id" => $locid,
            "device_data" => $devData,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function addExtraDeviceForSchoollocation($locid, $devData)
    {
        $api_url = "addExtraDeviceToSchoollocation.php";

        $postQueryArray = array(
            "location_id" => $locid,
            "device_data" => $devData,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getDeviceDetails($deviceId)
    {
        $api_url = 'getM4SDeviceDetails.php';

        $postQueryArray = array(
            "device_id" => $deviceId,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getExtraDeviceDetails($deviceId)
    {
        $api_url = 'getM4SExtraDeviceDetails.php';

        $postQueryArray = array(
            "device_id" => $deviceId,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getDevicesForCustomer($customerId)
    {
        $api_url = 'getM4SDeviceForCustomerId.php';

        $postQueryArray = array(
            "customer_id" => $customerId,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getExtraDevicesForCustomer($customerId)
    {
        $api_url = 'getM4SExtraDeviceForCustomerId.php';

        $postQueryArray = array(
            "customer_id" => $customerId,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function updateCustomerOnDevice($did, $cusid = NULL)
    {
        $api_url = 'updateCustomerOnDevice.php';
        $postQueryArray = array(
            "device_id" => $did,
            "customer_id" => $cusid,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function updateCustomerOnExtraDevice($did, $cusid = NULL)
    {
        $api_url = 'updateCustomerOnExtraDevice.php';
        $postQueryArray = array(
            "device_id" => $did,
            "customer_id" => $cusid,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function updateLocationOnDevice($did, $locid)
    {
        $api_url = 'updateLocationOnDevice.php';
        $postQueryArray = array(
            "device_id" => $did,
            "location_id" => $locid,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function updateLocationOnExtraDevice($did, $locid)
    {
        $api_url = 'updateLocationOnExtraDevice.php';
        $postQueryArray = array(
            "device_id" => $did,
            "location_id" => $locid,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getCustomerForId($customerId)
    {
        $api_url = 'getCustomerForId.php';
        $postQueryArray = array(
            "customer_id" => $customerId,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getCustomerForNameAndEmail($firstname, $lastname, $email)
    {
        $api_url = 'getCustomerForNameAndMail.php';
        $postQueryArray = array(
            "firstname" => utf8_encode($firstname),
            "lastname" => utf8_encode($lastname),
            "email" => $email,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getCustomerForNameAndLocation($firstname, $lastname, $locationId)
    {
        $api_url = 'getCustomerForNameAndLocation.php';
        $postQueryArray = array(
            "firstname" => $firstname,
            "lastname" => $lastname,
            "location" => $locationId,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function updateLocationOnCustomer($cid, $locid)
    {
        $api_url = 'updateLocationOnCustomer.php';
        $postQueryArray = array(
            "customer_id" => $cid,
            "location_id" => $locid,
            "date" => (new DateTime())->format('U'),
        );
        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getFieldServicesForSerial($serial)
    {
        $api_url = 'getFieldServicesForSerial.php';

        $postQueryArray = array(
            "serial" => $serial,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getSPSynergyBySchoolM4SId($schoolId)
    {
        $api_url = 'getSchoolSynergyIdForSchoolId.php';

        $postQueryArray = array(
            "schoolId" => $schoolId,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getFieldServiceDetails($fieldServiceId, $lang)
    {
        $api_url = 'getFieldServiceDetails.php';
        $postQueryArray = array(
            "fieldservice_id" => strval($fieldServiceId),
            "lang" => $lang,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getForecasts($synergyId, $accademicYear)
    {
        $api_url = 'getForecasts.php';

        $postQueryArray = array(
            "synergy_id" => strval($synergyId),
            "academicYear" => $accademicYear,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getForecastsDetails($synergyId, $accademicYear)
    {
        $api_url = 'getForecastsDetails.php';

        $postQueryArray = array(
            "synergy_id" => strval($synergyId),
            "academicYear" => $accademicYear,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getAllLeermiddel($synergy)
    {
        $api_url = 'getAllLeermiddelForSynergy.php';

        $postQueryArray = array(
            "synergy_id" => strval($synergy),
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getLeermiddel($synergyId, $accademicYear)
    {
        $api_url = 'getLeermiddelen.php';

        $postQueryArray = array(
            "synergy_id" => strval($synergyId),
            "academicYear" => $accademicYear,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getLeermiddelDetails($synergyId, $accademicYear)
    {
        $api_url = 'getLeermiddelenDetails.php';

        $postQueryArray = array(
            "synergy_id" => strval($synergyId),
            "academicYear" => $accademicYear,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getWebshop($synergyId, $accademicYear)
    {
        $api_url = 'getWebshop.php';

        $postQueryArray = array(
            "synergy_id" => strval($synergyId),
            "academicYear" => $accademicYear,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getWebshopDetails($synergyId, $accademicYear)
    {
        $api_url = 'getWebshopDetails.php';

        $postQueryArray = array(
            "synergy_id" => strval($synergyId),
            "academicYear" => $accademicYear,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getDeliveriesForSchool($synergyId, $accademicYear)
    {
        $api_url = 'getDeliveriesForSchool.php';

        $postQueryArray = array(
            "synergy_id" => strval($synergyId),
            "accademicyear" => $accademicYear,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getImageIntakesForSchool($synergyId, $accademicYear)
    {
        $api_url = 'getImagesIntakesForSchool.php';

        $postQueryArray = array(
            "synergy_id" => strval($synergyId),
            "accademicyear" => $accademicYear,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getSoftwareRequests($state)
    {
        $api_url = 'getSoftwareRequests.php';

        $postQueryArray = array(
            "state" => strval($state),
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getHardwareHashForDevice($serial)
    {
        $api_url = 'getHardwareHashForDevice.php';

        $postQueryArray = array(
            "device_serial" => strval($serial),
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getM4SDevicesModel()
    {
        $api_url = 'getM4SDevicesModel.php';

        $postQueryArray = array(
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getM4SDevicesSerial($serial)
    {
        $api_url = 'getM4SDevicesSerial.php';
        $postQueryArray = array(
            "serial" => $serial,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getM4SDevicesLabel($label)
    {
        $api_url = 'getM4SDevicesLabel.php';
        $postQueryArray = array(
            "label" => $label,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getM4SExtraDevicesSerial($serial)
    {
        $api_url = 'getM4SExtraDevicesSerial.php';
        $postQueryArray = array(
            "serial" => $serial,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getDeliveryGuidForSchool($synergy)
    {
        $api_url = 'getDeliveryGuidForSchool.php';

        $postQueryArray = array(
            "synergy_id" => strval($synergy),
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function removeCustomer($cid)
    {
        $api_url = 'removeCustomer.php';

        $postQueryArray = array(
            "customer_id" => strval($cid),
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function updateCustomer($cid, $firstname, $lastname, $email, $type, $freefields)
    {
        $api_url = 'updateCustomer.php';
        $postQueryArray = array(
            "customer_id" => strval($cid),
            "firstname" => $firstname,
            "lastname" => $lastname,
            "email" => $email,
            "type" => $type,
            "freefields" => json_encode($freefields),
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function updateDevice($did, $freefields)
    {
        $api_url = 'updateDevice.php';

        $postQueryArray = array(
            "device_id" => strval($did),
            "freefields" => json_encode($freefields),
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getLabelByOrder($order)
    {
        $api_url = 'getLabelForUsedFor.php';

        $postQueryArray = array(
            "used_for" => strval($order),
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getLeermiddelPowerBI($synergy)
    {
        $api_url = 'getLeermiddelPowerBI.php';

        $postQueryArray = array(
            "synergy_id" => strval($synergy),
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function removeExtraDevice($exid)
    {
        $api_url = 'removeExtraDevice.php';

        $postQueryArray = array(
            'extradevice_id' => $exid,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function removeDevice($did)
    {
        $api_url = 'removeDevice.php';

        $postQueryArray = array(
            'device_id' => $did,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function moveCustomerToSchool($cid, $school_id)
    {
        $api_url = 'updateInstitutionOnCustomer.php';

        $postQueryArray = array(
            'customer_id' => $cid,
            'school_id' => $school_id,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function moveDeviceToSchool($did, $cid, $school_id)
    {
        $api_url = 'updateInstitutionOnDevice.php';

        $postQueryArray = array(
            'device_id' => $did,
            'customer_id' => $cid,
            'school_id' => $school_id,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function moveExtraDeviceToSchool($exid, $cid, $school_id)
    {
        $api_url = 'updateInstitutionOnExtraDevice.php';

        $postQueryArray = array(
            'extradevice_id' => $exid,
            'customer_id' => $cid,
            'school_id' => $school_id,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getParentsForCustomer($cid)
    {
        $api_url = 'getParentsForCustomer.php';

        $postQueryArray = array(
            'customer_id' => $cid,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function addParent($cid, $parentData)
    {
        $api_url = 'addParent.php';

        $postQueryArray = array(
            'customer_id' => $cid,
            'parent' => $parentData,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function removeParent($cid, $pid)
    {
        $api_url = 'removeParent.php';

        $postQueryArray = array(
            'customer_id' => $cid,
            'parent_id' => $pid,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function editInstitutionlocation($data)
    {
        $api_url = 'updateLocation.php';

        $postQueryArray = array(
            'location_data' => json_encode($data),
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function freeFieldForDevicesOnSchool($schoolId, $action, $fieldTag = NULL, $defaultValue = NULL)
    {
        $api_url = 'freeFieldForDevicesOnSchool.php';

        $postQueryArray = array(
            'school_id' => $schoolId,
            'field_tag' => $fieldTag,
            'action' => $action,
            'default_value' => $defaultValue,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function freeFieldForCustomerOnSchool($schoolId, $action, $fieldTag = NULL, $defaultValue = NULL)
    {
        $api_url = 'freeFieldForCustomerOnSchool.php';

        $postQueryArray = array(
            'school_id' => $schoolId,
            'field_tag' => $fieldTag,
            'action' => $action,
            'default_value' => $defaultValue,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getCustomerForDevice($deviceId)
    {
        $api_url = 'getCustomerForDevice.php';

        $postQueryArray = array(
            'device_id' => $deviceId,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function updateSchoolOnCustomer($cusid, $synergy)
    {
        $api_url = 'updateSchoolOnCustomer.php';

        $postQueryArray = array(
            'customer_id' => $cusid,
            'synergy' => $synergy,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function updateSchoolOnDevice($did, $synergy)
    {
        $api_url = 'updateSchoolOnDevice.php';

        $postQueryArray = array(
            'device_id' => $did,
            'synergy' => $synergy,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getM4SExtraDevicesBySchoolId($synergy)
    {
        $api_url = 'getM4SExtraDevicesBySchoolId.php';

        $postQueryArray = array(
            'synergy_id' => $synergy,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function updateSchoolOnExtraDevice($did, $synergy)
    {
        $api_url = 'updateSchoolOnExtraDevice.php';

        $postQueryArray = array(
            'device_id' => $did,
            'synergy' => $synergy,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function searchWithCriteria($criteria, $institutions = null)
    {
        $api_url = 'searchWithCriteria.php';

        $postQueryArray = array(
            'criteria' => $criteria,
            'institutions' => (is_null($institutions)) ? null : json_encode($institutions),
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function addInternalTicket($ticket)
    {
        $api_url = 'addInternalTicket.php';
        $postQueryArray = array(
            'ticket' => $ticket,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getInternalTicketsForDevice($device)
    {
        $api_url = 'getInternalTicketsForDevice.php';
        $postQueryArray = array(
            'device_id' => $device,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getInternalTicketsForId($ticket)
    {
        $api_url = 'getInternalTicketsForId.php';
        $postQueryArray = array(
            'ticket_id' => $ticket,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function updateInternalTicket($ticket)
    {
        $api_url = 'updateInternalTicket.php';
        $postQueryArray = array(
            'ticket' => $ticket,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getInteralTicketForSchool($synergy)
    {
        $api_url = 'getInteralTicketForSchool.php';
        $postQueryArray = array(
            'synergy_id' => $synergy,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    public function getInteralTicketForSchoolLocation($locationId)
    {
        $api_url = 'getInteralTicketForSchoolLocation.php';
        $postQueryArray = array(
            'location_id' => $locationId,
            "date" => (new DateTime())->format('U'),
        );

        return $this->getApiRequest($api_url, $postQueryArray);
    }

    private function getApiRequest($api_url, $postQueryArray)
    {
        $sp2_username = $this->container->getParameter('app.sp2.username');
        $sp2_passwd = $this->container->getParameter('app.sp2.passwd');

        $postQueryJson = json_encode($postQueryArray);
        $postQueryEncrypted = $this->crypt->encrypt($postQueryJson);
        $formData = new FormDataPart(array(
            "data" => $postQueryEncrypted
        ));
        $parameters = array();
        if ($this->isDev) {
            $parameters = array(
//                'auth_ntlm' => array($sp2_username . ':' . $sp2_passwd),
                'headers' => $formData->getPreparedHeaders()->toArray(),
                'body' => $formData->bodyToString(),
            );
        } else {
            $parameters = array(
                'auth_ntlm' => array($sp2_username . ':' . $sp2_passwd),
                'headers' => $formData->getPreparedHeaders()->toArray(),
                'body' => $formData->bodyToString(),
            );
        }

        $response = $this->client->request(
            'POST',
            $this->container->getParameter('app.sp2.url') . '/api/' . $api_url,
            $parameters
        );

        $array = json_decode($response->getContent(), true);

        if (isset($array['responseValue'])) {
            $data = $this->crypt->decrypt($array['responseValue']);
            return json_decode($data, true);
        }

        return array();
    }

	public function addBlock($webshopId, $title, $content, $schoolLogo, $courseLabel)
	{
		$api_url = 'addBlock.php';
		$postQueryArray = array(
			"webshop_id" => $webshopId,
			"title" => $title,
			"content" => $content,
			"school_logo" => $schoolLogo,
			"course_label" => $courseLabel,
			"date" => (new DateTime())->format('U'),
		);

		return $this->getApiRequest($api_url, $postQueryArray);
	}

	public function getBlock($webshopId)
	{
		$api_url = 'getBlock.php';
		$postQueryArray = array(
			'webshop_id' => $webshopId,
			"date" => (new DateTime())->format('U'),
		);

		return $this->getApiRequest($api_url, $postQueryArray);
	}

	public function updateBlock($webshopId, $title, $content, $schoolLogo, $courseLabel)
	{
		$api_url = 'updateBlock.php';
		$postQueryArray = array(
			'webshop_id' => $webshopId,
			'title' => $title,
			"content" => $content,
			"school_logo" => $schoolLogo,
			"course_label" => $courseLabel,
			"date" => (new DateTime())->format('U'),
		);

		return $this->getApiRequest($api_url, $postQueryArray);
	}

	public function addExtTranslations($locale, $objectClass, $field, $foreignKey, $content)
	{
		$api_url = 'addExtTranslations.php';
		$postQueryArray = array(
			"locale" => $locale,
			"object_class" => $objectClass,
			"field" => $field,
			"foreign_key" => $foreignKey,
			"content" => $content,
			"date" => (new DateTime())->format('U'),
		);

		return $this->getApiRequest($api_url, $postQueryArray);
	}

	public function getExtTranslations($foreignKey)
	{
		$api_url = 'getExtTranslations.php';
		$postQueryArray = array(
			'foreign_key' => $foreignKey,
			"date" => (new DateTime())->format('U'),
		);

		return $this->getApiRequest($api_url, $postQueryArray);
	}

	public function updateExtTranslations($locale, $field, $foreignKey, $content)
	{
		$api_url = 'updateExtTranslations.php';
		$postQueryArray = array(
			"locale" => $locale,
			"field" => $field,
			"content" => $content,
			"foreign_key" => $foreignKey,
			"date" => (new DateTime())->format('U'),
		);

		return $this->getApiRequest($api_url, $postQueryArray);
	}

	public function getWebshopOrdersM4S($SynergyId)
	{
		$api_url = 'getWebshopOrdersM4S.php';
		$postQueryArray = array(
			"synergy_id" => $SynergyId,
			"date" => (new DateTime())->format('U'),
		);

		return $this->getApiRequest($api_url, $postQueryArray);
	}

	public function freeFieldForByodShop($schoolId, $action, $fieldId = NULL, $fieldTitle = NULL, $fieldType = NULL, $active = true, $required = false )
	{
		$api_url = 'freeFieldForByodShop.php';

		$postQueryArray = array(
			'field_id' => $fieldId,
			'school_id' => $schoolId,
			'field_title' => $fieldTitle,
			'action' => $action,
			'field_type' => $fieldType,
			'active' => $active,
			'required' => $required,
			"date" => (new DateTime())->format('U'),
		);

		return $this->getApiRequest($api_url, $postQueryArray);
	}

	public function getSchoolSettings(int $schoolId)
	{
		$api_url = 'getSchoolSettings.php';

		$postQueryArray = array(
			"school_id" => $schoolId,
			"date" => (new DateTime())->format('U'),
		);
		return $this->getApiRequest($api_url, $postQueryArray);
	}

	public function updateSchoolSettings(int $schoolId, int $useSchoolFacturationDefault)
	{
		$api_url = 'updateSchoolSettings.php';

		$postQueryArray = [
			'school_id' => $schoolId,
			'use_school_facturation_default' => $useSchoolFacturationDefault,
			'date' => (new DateTime())->format('U'),
		];

		return $this->getApiRequest($api_url, $postQueryArray);
	}
}
