<?php

namespace App\Controller;

use App\Entity\Device;

use App\Entity\Institution;
use App\Entity\InstitutionLocation;
use App\Entity\Ticket;
use App\Entity\MoveCustomerLog;
use App\Entity\MoveDeviceLog;
use App\Entity\User;
use App\Form\BulkMoveType;
use App\Form\CustomerSelectionType;
use App\Form\DeviceType;
use App\Form\TicketType;
use App\Form\MoveDeviceLogType;
use App\Repository\InstitutionRepository;
use App\Repository\LoanRepository;
use App\Repository\MoveDeviceLogRepository;
use App\Service\SP2ApiService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InventoryController extends AbstractController
{
    private $api;
    private $mdlr;
    private $lr;
    private $ir;
    private $em;

    public function __construct(SP2ApiService $api, MoveDeviceLogRepository $mdlr, LoanRepository $lr, InstitutionRepository $ir, EntityManagerInterface $em)
    {
        $this->api = $api;
        $this->mdlr = $mdlr;
        $this->lr = $lr;
        $this->ir = $ir;
        $this->em = $em;
    }

    /**
     * @Route("/inventory", name="inventory")
     */
    public function index(Request $request): Response
    {
        $cookies = $request->cookies;
        if ($cookies->has('institution_id') && $cookies->get('institution_id') != '') {
            /** @var Institution $institution */
            $institution = $this->ir->find($cookies->get('institution_id'));
            if (!$institution) {
                throw new \Exception('institution not found');
            }
            return $this->redirectToRoute('inventoryForInstitution', array(
                "synergyid" => $institution->getSynergyId()
            ));
        } else if ($cookies->has('location_id') && $cookies->get('location_id') != "") {
            return $this->redirectToRoute('inventoryForInsitutionlocation', array(
                "locationid" => $cookies->get('location_id')
            ));
        }
        $this->addFlash("info", "select an Institution first");
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/inventory/assigndevicetolocation", name="assigndevicetolocation")
     */
    public function assigndevicetolocation(Request $request): Response
    {
        $counter = 0;
        $synergy = $request->get('synergy');
        $withUser = null;
        if ($request->get('withUser')) {
            $withUser = $request->get('withUser');
        }
        $locationId = $request->request->get('location');
        foreach ($request->request->all() as $key => $value) {
            if (is_numeric($key)) {
                if ($withUser) {
                    $customerId = $this->api->getCustomerForDevice($key);
                    $this->api->updateLocationOnCustomer($customerId['customer_id'], $locationId);
                }
                $this->api->updateLocationOnDevice($key, $locationId);
                $counter++;
            }
        }
        $this->addFlash('success', $counter . " toestellen verhuisd");
        return $this->redirectToRoute('inventoryForInstitution', array(
            "synergyid" => $synergy
        ));
    }

    /**
     * @Route("/inventory/{synergyid}", name="inventoryForInstitution")
     */
    public function inventoryForInstitution(Request $request): Response
    {
        $synergyId = $request->get('synergyid');
        $allInventory = array();
        /** @var User $user */
        $user = $this->getUser();
        if ($user->hasInstitutionBySid($synergyId) === 0 && !$user->isAdmin()) {
            throw new \Exception();
        }

        $withuser = array();
        $withoutuser = array();
        $withNoLocation = $this->api->getDevicesForInstitution($synergyId, true);
        $inventory = $this->api->getDevicesForInstitution($synergyId, false);
        $extraDevices = $this->api->getM4SExtraDevicesBySchoolId($synergyId);

        // BulkMoveForm
        $bulkmoveform = $this->createForm(BulkMoveType::class, null, array(
            'synergy' => $synergyId,
            'user' => $user->getId(),
            'usedin' => 'device'
        ));
        $bulkmoveform->handleRequest($request);
        if ($bulkmoveform->isSubmitted() && $bulkmoveform->isValid()) {

            if ($bulkmoveform->get('institutions')->getData()['institutions'] !== NULL && $bulkmoveform->get('locations')->getData()['locations'] === NULL) {
                /** @var Institution $institution */
                $institution = $bulkmoveform->get('institutions')->getData()['institutions'];
                if (!empty($request->get('device'))) {
                    foreach ($request->get('device') as $did) {
                        // Unset customer
                        $this->api->updateCustomerOnDevice($did);

                        // Unset location
                        $this->api->updateLocationOnDevice($did, NULL);

                        // Move the device to new institution
                        $this->api->updateSchoolOnDevice($did, $institution->getSynergyId());
                    }
                    return $this->redirectToRoute('inventoryForInstitution', array(
                        "synergyid" => $synergyId
                    ));
                }

                if (!empty($request->get('extradevice'))) {
                    foreach ($request->get('extradevice') as $eid) {
                        // Unset customer
                        $this->api->updateCustomerOnExtraDevice($eid);

                        //unset location
                        $this->api->updateLocationOnExtraDevice($eid, NULL);

                        // move the device to new institution
                        $this->api->updateSchoolOnExtraDevice($eid, $institution->getSynergyId());
                    }
                    return $this->redirectToRoute('inventoryForInstitution', array(
                        "synergyid" => $synergyId
                    ));
                }
            } elseif ($bulkmoveform->get('locations')->getData()['locations'] !== NULL && $bulkmoveform->get('institutions')->getData()['institutions'] === NULL) {
                /** @var InstitutionLocation $location */
                $location = $bulkmoveform->get('locations')->getData()['locations'];
                if (!empty($request->get('device'))) {
                    foreach ($request->get('device') as $did) {
                        // Unset customer
                        $this->api->updateCustomerOnDevice($did);
                        // Move the device to new location
                        $this->api->updateLocationOnDevice($did, $location->getId());
                    }
                    return $this->redirectToRoute('inventoryForInstitution', array(
                        "synergyid" => $synergyId
                    ));
                }

                if (!empty($request->get('extradevice'))) {
                    foreach ($request->get('extradevice') as $eid) {
                        //unset customer
                        $this->api->updateCustomerOnExtraDevice($eid);
                        //Move the device to new location
                        $this->api->updateLocationOnExtraDevice($eid, $location->getId());
                    }
                    return $this->redirectToRoute('inventoryForInstitution', array(
                        "synergyid" => $synergyId
                    ));
                }
            } else {
                $this->addFlash("warning", "Select an institution or a location NOT both");
            }
        }

        foreach ($inventory as $invent) {
            //foreach ($invent as $device) {
                if ($invent['customer_id'] == NULL) {
                    array_push($withoutuser, $invent);
                } else {
                    array_push($withuser, $invent);
                }
            //}
        }

        foreach ($inventory as $invent) {
            array_push($allInventory, $invent);
        }
        foreach ($withNoLocation as $invent) {
            array_push($allInventory, $invent);
        }

        return $this->render('inventory/inventorydata.html.twig', array(
            'dataWithUser' => $withuser,
            'dataWithoutUser' => $withoutuser,
            'dataWithouLocation' => $withNoLocation,
            'extradevices' => $extraDevices,
            'data' => $allInventory,
            'bulkmoveform' => $bulkmoveform->createView(),
        ));
    }

    /**
     * @Route("/inventory/location/{locationid}", name="inventoryForInsitutionlocation")
     */
    public function inventoryForInstitutionlocation(Request $request): Response
    {
        $locationId = $request->get('locationid');
        $withuser = array();
        $withoutuser = array();
        $inventory = $this->api->getDevicesForInstitutionLocation($locationId);
        $location = $this->api->getSchoollocation($locationId);
        $synergyId = $location[0]['synergyid'];
        /** @var User $user */
        $user = $this->getUser();
        if ($user->hasInstitutionBySid($location[0]['synergyid']) === 0 && !$user->isAdmin()) {
            throw new \Exception();
        }

        // get internal devices for locationId
        $extradevices = $this->api->getExtraDevicesForInstitutionLocation($locationId);

        // BulkMoveForm
        $bulkmoveform = $this->createForm(BulkMoveType::class, null, array(
            'synergy' => $synergyId,
            'user' => $user->getId(),
            'usedin' => 'device'
        ));
        $bulkmoveform->handleRequest($request);
        if ($bulkmoveform->isSubmitted() && $bulkmoveform->isValid()) {

            if ($bulkmoveform->get('institutions')->getData()['institutions'] !== NULL && $bulkmoveform->get('locations')->getData()['locations'] === NULL) {
                /** @var Institution $institution */
                $institution = $bulkmoveform->get('institutions')->getData()['institutions'];
                if (!empty($request->get('device'))) {
                    foreach ($request->get('device') as $did) {
                        // Unset customer
                        $this->api->updateCustomerOnDevice($did);

                        // Unset location
                        $this->api->updateLocationOnDevice($did, NULL);

                        // Move the device to new institution
                        $this->api->updateSchoolOnDevice($did, $institution->getSynergyId());
                    }
                    return $this->redirectToRoute('inventoryForInsitutionlocation', array(
                        "locationid" => $locationId
                    ));
                }

                if (!empty($request->get('extradevice'))) {
                    foreach ($request->get('extradevice') as $eid) {
                        // Unset customer
                        $this->api->updateCustomerOnExtraDevice($eid);

                        //unset location
                        $this->api->updateLocationOnExtraDevice($eid, NULL);

                        // move the device to new institution
                        $this->api->updateSchoolOnExtraDevice($eid, $institution->getSynergyId());
                    }
                    return $this->redirectToRoute('inventoryForInsitutionlocation', array(
                        "locationid" => $$locationId
                    ));
                }
            } elseif ($bulkmoveform->get('locations')->getData()['locations'] !== NULL && $bulkmoveform->get('institutions')->getData()['institutions'] === NULL) {
                /** @var InstitutionLocation $location */
                $loc = $bulkmoveform->get('locations')->getData()['locations'];
                if (!empty($request->get('device'))) {
                    foreach ($request->get('device') as $did) {
                        // Unset customer
                        $this->api->updateCustomerOnDevice($did);
                        // Move the device to new location
                        $this->api->updateLocationOnDevice($did, $loc->getId());
                    }
                    return $this->redirectToRoute('inventoryForInsitutionlocation', array(
                        "locationid" => $$locationId
                    ));
                }

                if (!empty($request->get('extradevice'))) {
                    foreach ($request->get('extradevice') as $eid) {
                        //unset customer
                        $this->api->updateCustomerOnExtraDevice($eid);
                        //Move the device to new location
                        $this->api->updateLocationOnExtraDevice($eid, $loc->getId());
                    }
                    return $this->redirectToRoute('inventoryForInsitutionlocation', array(
                        "locationid" => $$locationId
                    ));
                }
            } else {
                $this->addFlash("warning", "Select an institution or a location NOT both");
            }
        }

        foreach ($inventory as $device) {
            if ($device['customer_id'] == NULL) {
                array_push($withoutuser, $device);
            } else {
                array_push($withuser, $device);
            }
        }

        return $this->render('inventory/inventorydata.html.twig', array(
            'dataWithUser' => $withuser,
            'dataWithoutUser' => $withoutuser,
            'dataWithouLocation' => [],
            'extradevices' => $extradevices,
            "locationid" => $locationId,
            'data' => $inventory,
            'bulkmoveform' => $bulkmoveform->createView()
        ));
    }

    /**
     * @Route("/inventory/devicedetails/{id}", name="invetoryDeviceDetails")
     */
    public function inventoryDeviceDetails(Request $request): Response
    {
        $did = $request->get('id');

        $device = $this->api->getDeviceDetails($did);
        $locationId = null;
        $location = null;
        if (isset($device[0]['schoollocation_id'])) {
            $locationId = $device[0]['schoollocation_id'];
            $location = $this->api->getSchoollocation($locationId);

            /** @var User $user */
            $user = $this->getUser();
            if ($user->hasInstitutionBySid($location[0]['synergyid']) === 0 && !$user->isAdmin()) {
                throw new Exception();
            }
        }

        $schoolid = ($device[0]['school_id']) ? $device[0]['school_id'] : $location[0]['school_id'];

        $moves = $this->mdlr->findByDevice($did);
        $loans = $this->lr->findByDeviceId($did);
        $hardwareHash = $this->api->getHardwareHashForDevice($device[0]['serialnumber']);
        $fieldservices = $this->api->getFieldServicesForSerial($device[0]['serialnumber']);
        $internalTickets =  $this->api->getInternalTicketsForDevice($did);

        // CustomerSelectionForm
        $form = $this->createForm(CustomerSelectionType::class, null, array(
            'schoollocationId' => $locationId,
        ));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->api->updateCustomerOnDevice($did, $form->getData()['user']);
            return $this->redirectToRoute('invetoryDeviceDetails', array(
                'id' => $did,
            ));
        }

        // EditCustomer form
        $editForm = $this->createForm(DeviceType::class, null, array(
            'freefieldtag01' => $device[0]['freefieldtag01'],
            'freefieldtag02' => $device[0]['freefieldtag02'],
            'freefieldtag03' => $device[0]['freefieldtag03'],
            'freefieldtag04' => $device[0]['freefieldtag04'],
            'freefieldtag05' => $device[0]['freefieldtag05'],
        ));
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $freefields = array();
            if ($device[0]['freefieldtag01']) {
                $freefields['freefieldtag01'] = $editForm->get('freefieldtag01')->getData();
            }
            if ($device[0]['freefieldtag02']) {
                $freefields['freefieldtag02'] = $editForm->get('freefieldtag02')->getData();
            }
            if ($device[0]['freefieldtag03']) {
                $freefields['freefieldtag03'] = $editForm->get('freefieldtag03')->getData();
            }
            if ($device[0]['freefieldtag04']) {
                $freefields['freefieldtag04'] = $editForm->get('freefieldtag04')->getData();
            }
            if ($device[0]['freefieldtag05']) {
                $freefields['freefieldtag05'] = $editForm->get('freefieldtag05')->getData();
            }
            $this->api->updateDevice(
                $did,
                $freefields
            );
            return $this->redirectToRoute('invetoryDeviceDetails', array(
                'id' => $did,
            ));
        } else {
            if ($device[0]['freefieldtag01']) {
                $editForm->get('freefieldtag01')->setData($device[0]['freefieldvalue01']);
            }
            if ($device[0]['freefieldtag02']) {
                $editForm->get('freefieldtag02')->setData($device[0]['freefieldvalue02']);
            }
            if ($device[0]['freefieldtag03']) {
                $editForm->get('freefieldtag03')->setData($device[0]['freefieldvalue03']);
            }
            if ($device[0]['freefieldtag04']) {
                $editForm->get('freefieldtag04')->setData($device[0]['freefieldvalue04']);
            }
            if ($device[0]['freefieldtag05']) {
                $editForm->get('freefieldtag05')->setData($device[0]['freefieldvalue05']);
            }
        }

        // MoveDeviceFromSchoollocationForm
        $moveForm = $this->createForm(MoveDeviceLogType::class, null, array(
            'school_id' => $schoolid,
            'customer_id' => $device[0]['customerid']
        ));
        $moveForm->handleRequest($request);

        if ($moveForm->isSubmitted() && $moveForm->isValid()) {
            /** @var MoveDeviceLog $mdl */
            $mdl = new MoveDeviceLog();
            //$mdl = $moveForm->getData();
            $newloc = $this->api->getSchoollocation($moveForm->get('toLocationName')->getData());

            $mdl->setDeviceId($moveForm->get('deviceId')->getData());
            $mdl->setDeviceSerial($moveForm->get('deviceSerial')->getData());
            $mdl->setFromLocationId($moveForm->get('fromLocationId')->getData());
            $mdl->setFromLocationName($moveForm->get('fromLocationName')->getData());
            $mdl->setToLocationId($newloc[0]['id']);
            $mdl->setToLocationName($newloc[0]['institutionName']);
            $mdl->setWhenMoved($moveForm->get('whenMoved')->getData());
            $mdl->setUser($this->getUser());

            $this->api->updateLocationOnDevice($did, $newloc[0]['id']);

            $this->em->persist($mdl);
            $this->em->flush();

            // // move user if any AND when the user allows it.
            if ($device[0]['customerid'] !== null && $moveForm->get('moveCustomer')->getData() == true) {
                /** @var MoveCustomerLog $mcl */
                $mcl = new MoveCustomerLog();
                $mcl->setCustomerId($device[0]['customerid']);
                $mcl->setCustomerName($device[0]['firstname'] . " " . $device[0]['lastname']);
                $mcl->setFromLocationId($moveForm->get('fromLocationId')->getData());
                $mcl->setFromLocationName($moveForm->get('fromLocationName')->getData());
                $mcl->setToLocationId($newloc[0]['id']);
                $mcl->setToLocationName($newloc[0]['institutionName']);
                $mcl->setWhenMoved($moveForm->get('whenMoved')->getData());
                $mcl->setUser($this->getUser());
                $this->api->updateLocationOnCustomer($device[0]['customerid'], $newloc[0]['id']);
                $this->em->persist($mcl);
                $this->em->flush();
            }

            return $this->redirectToRoute('invetoryDeviceDetails', array(
                'id' => $did,
            ));
        } else {
            // $moveForm->get('user')->setData($this->getUser());
            $moveForm->get('deviceId')->setData($did);
            $moveForm->get('deviceSerial')->setData($device[0]['serialnumber']);
            if ($location !== null) {
                $moveForm->get('fromLocationId')->setData($location[0]['id']);
                $moveForm->get('fromLocationName')->setData($location[0]['institutionName']);
            }
        }

        //Internal FieldServiceForm
        $internalFSC = new Ticket();
        $internalFieldServiceForm = $this->createForm(TicketType::class, $internalFSC);
        $internalFieldServiceForm->handleRequest($request);

        if ($internalFieldServiceForm->isSubmitted() && $internalFieldServiceForm->isValid()) {
            $internalFSC->setDevice($did);
            $internalFSC->setCustomer(($device[0]['customerid'] !== null) ? $device[0]['customerid'] : null);
            $internalFSC->setSchoollocation($locationId);
            $internalFSC->setSchool($schoolid);
            $internalFSC->setState('open');
            $internalFSC->setProblem($internalFieldServiceForm->get('problem')->getData());
            $internalFSC->setDescription($internalFieldServiceForm->get('description')->getData());

            //Save interalFieldServiceCase
            $inserted = $this->api->addInternalTicket($internalFSC->toJSON());
            if (!empty($inserted)) {
                $this->addFlash('info', 'new ticket added: ' . $inserted['insertedid']);
            }

            return $this->redirectToRoute('invetoryDeviceDetails', array(
                'id' => $did,
            ));
        }

        return $this->render('inventory/inventory_details.html.twig', array(
            "device" => $device[0],
            "form" => $form->createView(),
            "editForm" => $editForm->createView(),
            "moveForm" => $moveForm->createView(),
            "moves" => $moves,
            "loans" => $loans,
            "fieldservices" => $fieldservices,
            "hardwareHash" => (empty($hardwareHash)) ? array() : $hardwareHash[0],
            "internalFSForm" => $internalFieldServiceForm->createView(),
            "tickets" => $internalTickets
        ));
    }

    /**
     * @Route("/inventory/fieldservices/{serial}", name="invetoryDeviceDetailsBySerial")
     */
    public function invetoryDeviceDetailsBySerial(Request $request): Response
    {
        $serial = $request->get('serial');
        $device = $this->api->getM4SDevicesSerial($serial);
        return $this->redirectToRoute("invetoryDeviceDetails", array(
            'id' => $device[0]['id']
        ));
    }

    /**
     * @Route("/inventory/forecasts/{label}", name="invetoryDeviceDetailsByLabel")
     */
    public function inventoryDeviceDetailsByLabel(Request $request): Response
    {
        $serial = $request->get('label');
        $device = $this->api->getM4SDevicesLabel($serial);
        if (empty($device)) {
            $this->addFlash('warning', 'No device found for label ' . $serial);
            return $this->redirectToRoute('forecast');
        }
        return $this->redirectToRoute("invetoryDeviceDetails", array(
            'id' => $device[0]['id']
        ));
    }

    /**
     * @Route("/inventory/extradevicedetails/{id}", name="invetoryExtraDeviceDetails")
     */
    public function inventoryExtraDeviceDetails(Request $request): Response
    {
        $exid = $request->get('id');
        $exd = $this->api->getExtraDeviceDetails($exid);
        $locationId = $exd[0]['schoollocationid'];
        $location = $this->api->getSchoollocation($locationId);
        $moves = $this->mdlr->findByDevice($exid);
        $loans = $this->lr->findByDeviceId($exid);
        //$locationId = $exd->getM4sSchoollocationId();
        //$location = $this->api->getSchoollocation($locationId);
        $customer = $this->api->getCustomerForId($exd[0]['customer_id']);
        $moves = $this->mdlr->findByDevice($exid);
        $loans = $this->lr->findByDeviceId($exid);

        $schoolid = ($exd[0]['school_id']) ? $exd[0]['school_id'] : $location[0]['school_id'];

        // CustomerSelectionForm
        $customerform = $this->createForm(CustomerSelectionType::class, null, array(
            'schoollocationId' => $locationId,
        ));
        $customerform->handleRequest($request);
        if ($customerform->isSubmitted() && $customerform->isValid()) {
            $this->api->updateCustomerOnExtraDevice($exid, $customerform->getData()['user']);
            return $this->redirectToRoute('invetoryExtraDeviceDetails', array(
                'id' => $exid,
            ));
        }

        // MoveDeviceFromSchoollocationForm
        $moveForm = $this->createForm(MoveDeviceLogType::class, null, array(
            'school_id' => $schoolid,
            'customer_id' => $exd[0]['customerid']
        ));
        $moveForm->handleRequest($request);

        if ($moveForm->isSubmitted() && $moveForm->isValid()) {
            /** @var MoveDeviceLog $mdl */
            $mdl = new MoveDeviceLog();
            //$mdl = $moveForm->getData();
            $newloc = $this->api->getSchoollocation($moveForm->get('toLocationName')->getData());

            $mdl->setDeviceId($moveForm->get('deviceId')->getData());
            $mdl->setDeviceSerial($moveForm->get('deviceSerial')->getData());
            $mdl->setFromLocationId($moveForm->get('fromLocationId')->getData());
            $mdl->setFromLocationName($moveForm->get('fromLocationName')->getData());
            $mdl->setToLocationId($newloc[0]['id']);
            $mdl->setToLocationName($newloc[0]['institutionName']);
            $mdl->setWhenMoved($moveForm->get('whenMoved')->getData());
            $mdl->setUser($this->getUser());

            $this->api->updateLocationOnExtraDevice($exid, $newloc[0]['id']);

            $this->em->persist($mdl);
            $this->em->flush();

            // // move user if any AND when the user allows it.
            if ($exid->getCustomer_id() !== null && $moveForm->get('moveCustomer')->getData() == true) {
                /** @var MoveCustomerLog $mcl */
                $mcl = new MoveCustomerLog();
                $mcl->setCustomerId($exid->getCustomer_id());
                $mcl->setCustomerName($customer[0]['firstname'] . " " . $customer[0]['lastname']);
                $mcl->setFromLocationId($moveForm->get('fromLocationId')->getData());
                $mcl->setFromLocationName($moveForm->get('fromLocationName')->getData());
                $mcl->setToLocationId($newloc[0]['id']);
                $mcl->setToLocationName($newloc[0]['institutionName']);
                $mcl->setWhenMoved($moveForm->get('whenMoved')->getData());
                $mcl->setUser($this->getUser());

                $this->api->updateLocationOnCustomer($exd->getCustomer_id(), $newloc[0]['id']);
                $this->em->persist($mcl);
                $this->em->flush();
            }

            return $this->redirectToRoute('invetoryExtraDeviceDetails', array(
                'id' => $exid,
            ));
        } else {
            // $moveForm->get('user')->setData($this->getUser());
            $moveForm->get('deviceId')->setData($exid);
            $moveForm->get('deviceSerial')->setData($exd[0]['productnumber']);
            $moveForm->get('fromLocationId')->setData(isset($location[0]) ? $location[0]['id'] : NULL);
            $moveForm->get('fromLocationName')->setData(isset($location[0]) ? $location[0]['institutionName'] : NULL);
        }

        return $this->render('inventory/extradevice_details.html.twig', array(
            "device" => $exd[0],
            "form" => $customerform->createView(),
            "moveForm" => $moveForm->createView(),
            "moves" => $moves,
            "loans" => $loans,
            "location" => empty($location) ? array() : $location[0],
            "customer" => (empty($customer)) ? array() : $customer[0]
        ));
    }

    /**
     * @Route("/inventory/devicedetails/{id}/remove", name="removeDeviceDetails")
     */
    public function removeDevice(Request $request)
    {
        $did = $request->get('id');
        // $this->api->updateCustomerOnExtraDevice($exid);
        $this->api->updateCustomerOnDevice($did);
        $this->api->removeDevice($did);

        $this->addFlash("info", "Device has been deleted");
        return $this->redirectToRoute('inventory');
    }
}
