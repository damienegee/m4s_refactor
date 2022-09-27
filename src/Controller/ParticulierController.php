<?php

namespace App\Controller;

use App\Entity\ContactPerson;
use App\Entity\Institution;
use App\Entity\User;

use App\Form\CustomerType;
use App\Entity\Customer;
use App\Entity\InstitutionLocation;
use App\Entity\MoveCustomerLog;
use App\Entity\MoveDeviceLog;
use App\Form\BulkMoveType;
use App\Form\DeviceSelectionType;
use App\Form\MoveCustomerFromInstitutionType;
use App\Form\MoveCustomerLogType;
use App\Form\ParentType;
use App\Repository\InstitutionRepository;
use App\Repository\MoveCustomerLogRepository;
use App\Service\SP2ApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ParticulierController extends AbstractController
{
    private $api;
    private $mclr;
    private $ir;
    private $em;

    public function __construct(SP2ApiService $api, MoveCustomerLogRepository $mclr, InstitutionRepository $ir, EntityManagerInterface $em)
    {
        $this->api = $api;
        $this->mclr = $mclr;
        $this->ir = $ir;
        $this->em = $em;
    }

    /**
     * @Route("/customer", name="customer")
     */
    public function index(Request $request): Response
    {
        $cookies = $request->cookies;
        if ($cookies->has('institution_id') && $cookies->get('institution_id') != '') {
            $institution = $this->ir->find($cookies->get('institution_id'));
            if (!$institution) {
                throw new \Exception('institution not found');
            }
            return $this->redirectToRoute('customerForInstitution', array(
                "synergyid" => $institution->getSynergyId()
            ));
        } else if ($cookies->has('location_id') && $cookies->get('location_id') != "") {
            return $this->redirectToRoute('customerForInstitutionlocation', array(
                'locationid' => $cookies->get('location_id')
            ));
        }
        $this->addFlash("info", "select an Institution first");
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/customer/assigncustomertolocation", name="assigncustomertolocation")
     */
    public function assigncustomerlocation(Request $request): Response
    {
        $synergy = $request->get('synergy');
        $locationId = $request->request->get('location');
        $counter = 0;
        foreach ($request->request->all() as $key => $value) {
            if (is_numeric($key)) {
                $this->api->updateLocationOnCustomer($key, $locationId);
                $counter++;
            }
        }
        $this->addFlash('success', $counter . " gebruiker verhuisd");
        return $this->redirectToRoute('inventoryForInstitution', array(
            "synergyid" => $synergy
        ));
    }

    /**
     * @Route("/customer/remove/{cid}", name="removecustomer")
     */
    public function removeCustomer(Request $request): Response
    {
        $cid = $request->get('cid');
        $devices = $this->api->getDevicesForCustomer($cid);
        $extradevices = $this->api->getExtraDevicesForCustomer($cid);

        foreach ($devices as $device) {
            $this->api->updateCustomerOnDevice($device['id']);
        }
        foreach ($extradevices as $ex) {
            $this->api->updateCustomerOnExtraDevice($ex['id']);
        }
        $this->api->removeCustomer($cid);
        $this->addFlash("info", "User has been deleted");
        return $this->redirectToRoute('customer');
    }


    /**
     * @Route("/customer/{synergyid}", name="customerForInstitution")
     */
    public function customerForInstitution(Request $request): Response
    {
        $synergyid = $request->get('synergyid');

        /** @var User $user */
        $user = $this->getUser();
        if ($user->hasInstitutionBySid($synergyid) === 0 && !$user->isAdmin()) {
            throw new \Exception();
        }

        $bulkmoveForm = $this->createForm(BulkMoveType::class, null, array(
            'synergy' => $synergyid,
            'user' => $user->getId(),
            'usedin' => 'customer'
        ));
        $bulkmoveForm->handleRequest($request);

        if ($bulkmoveForm->isSubmitted() && $bulkmoveForm->isValid()) {
            if ($bulkmoveForm->get('type')->getData()['type'] !== NULL) {
                if (!empty($request->get('customer'))) {
                    foreach ($request->get('customer') as $cid) {
                        $customer = $this->api->getCustomerForId($cid);
                        $this->api->updateCustomer(
                            $cid,
                            $customer[0]['firstname'],
                            $customer[0]['lastname'],
                            $customer[0]['email'],
                            $bulkmoveForm->get('type')->getData()['type'],
                            array()
                        );
                    }
                }
            }
            if ($bulkmoveForm->get('institutions')->getData()['institutions'] !== NULL && $bulkmoveForm->get('locations')->getData()['locations'] === NULL) {
                /** @var Institution $institution */
                $institution = $bulkmoveForm->get('institutions')->getData()['institutions'];
                if (!empty($request->get('customer'))) {
                    foreach ($request->get('customer') as $cid) {
                        //remove devices if any;
                        $devices = $this->api->getDevicesForCustomer($cid);
                        foreach ($devices as $device) {
                            $this->api->updateCustomerOnDevice($device['id']);
                        }
                        //unset the location
                        $this->api->updateLocationOnCustomer($cid, null);
                        // move customer to new location
                        $this->api->updateSchoolOnCustomer($cid, $institution->getSynergyId());
                    }
                }
            } elseif ($bulkmoveForm->get('locations')->getData()['locations'] !== NULL && $bulkmoveForm->get('institutions')->getData()['institutions'] === NULL) {
                /** @var InstitutionLocation $location */
                $location = $bulkmoveForm->get('locations')->getData()['locations'];
                if (!empty($request->get('customer'))) {
                    foreach ($request->get('customer') as $cid) {
                        //remove devices if any;
                        $devices = $this->api->getDevicesForCustomer($cid);
                        foreach ($devices as $device) {
                            $this->api->updateCustomerOnDevice($device['id']);
                        }
                        //move to new location
                        $this->api->updateLocationOnCustomer($cid, $location->getId());
                    }
                }
            } elseif ($bulkmoveForm->get('locations')->getData()['locations'] === NULL && $bulkmoveForm->get('institutions')->getData()['institutions'] === NULL) {
                // nothing to do even not showing a warning
            } else {
                $this->addFlash("warning", "Select an institution or a location NOT both");
                return $this->redirectToRoute("customerForInstitution", array(
                    "synergyid" => $synergyid
                ));
            }

            return $this->redirectToRoute("customerForInstitution", array(
                "synergyid" => $synergyid
            ));
        }

        $customers = $this->api->getCustomerForInstitution($synergyid, false);
        $withoutLocation = $this->api->getCustomerForInstitution($synergyid, true);

        $locations = $this->api->getSchoollocationsForSchool($synergyid);

        return $this->render('particulier/customerdata.html.twig', array(
            "data" => $customers,
            "withoutlocation" => $withoutLocation,
            "synergy" => $synergyid,
            'locations' => $locations,
            'bulkmoveForm' => $bulkmoveForm->createView()
        ));
    }

    /**
     * @Route("customer/location/add", name="addcustomerForInstitutionlocation")
     */
    public function addCustomerForLocation(Request $request): Response
    {
        $locationId = $request->get('locationid');

        $form = $this->createForm(CustomerType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Customer $customer */
            $customer = $form->getData();

            $result = $this->api->addCustomerForSchoollocation($locationId, $customer->toJSON());

            if ($result) {
                return $this->redirectToRoute('customerForInstitutionlocation', array(
                    "locationid" => $locationId
                ));
            }
        }

        return $this->render('particulier/form.html.twig', array(
            "form" => $form->createView()
        ));
    }

    /**
     * @Route("customer/location/{locationid}", name="customerForInstitutionlocation")
     */
    public function customerForInstitutionlocation(Request $request): Response
    {
        $locationId = $request->get('locationid');
        $customers = $this->api->getCustomerForInstitutionLocation($locationId);

        /** @var User $user */
        $user = $this->getUser();
        $location = $this->api->getSchoollocation($locationId);
        $synergyid = trim($location[0]['synergyid']);
        $bulkmoveForm = $this->createForm(BulkMoveType::class, null, array(
            'synergy' => $synergyid,
            'user' => $user->getId(),
            'usedin' => 'customer'
        ));
        $bulkmoveForm->handleRequest($request);

        if ($bulkmoveForm->isSubmitted() && $bulkmoveForm->isValid()) {
            if ($bulkmoveForm->get('type')->getData()['type'] !== NULL) {
                if (!empty($request->get('customer'))) {
                    foreach ($request->get('customer') as $cid) {
                        $customer = $this->api->getCustomerForId($cid);
                        $this->api->updateCustomer(
                            $cid,
                            $customer[0]['firstname'],
                            $customer[0]['lastname'],
                            $customer[0]['email'],
                            $bulkmoveForm->get('type')->getData()['type'],
                            array()
                        );
                    }
                }
            }

            if ($bulkmoveForm->get('institutions')->getData()['institutions'] !== NULL && $bulkmoveForm->get('locations')->getData()['locations'] === NULL) {
                /** @var Institution $institution */
                $institution = $bulkmoveForm->get('institutions')->getData()['institutions'];
                if (!empty($request->get('customer'))) {
                    foreach ($request->get('customer') as $cid) {
                        //remove devices if any;
                        $devices = $this->api->getDevicesForCustomer($cid);
                        foreach ($devices as $device) {
                            $this->api->updateCustomerOnDevice($device['id']);
                        }
                        //unset the location
                        $this->api->updateLocationOnCustomer($cid, null);
                        // move customer to new location
                        $this->api->updateSchoolOnCustomer($cid, $institution->getSynergyId());
                    }
                }
            } elseif ($bulkmoveForm->get('locations')->getData()['locations'] !== NULL && $bulkmoveForm->get('institutions')->getData()['institutions'] === NULL) {
                /** @var InstitutionLocation $location */
                $loc = $bulkmoveForm->get('locations')->getData()['locations'];
                if (!empty($request->get('customer'))) {
                    foreach ($request->get('customer') as $cid) {
                        //remove devices if any;
                        $devices = $this->api->getDevicesForCustomer($cid);
                        foreach ($devices as $device) {
                            $this->api->updateCustomerOnDevice($device['id']);
                        }
                        //move to new location
                        $this->api->updateLocationOnCustomer($cid, $loc->getId());
                    }
                }
            } elseif ($bulkmoveForm->get('locations')->getData()['locations'] === NULL && $bulkmoveForm->get('institutions')->getData()['institutions'] === NULL) {
                // nothing to do. not even showing a warning
            } else {
                $this->addFlash("warning", "Select an institution or a location NOT both");
                return $this->redirectToRoute("customerForInstitutionlocation", array(
                    "locationid" => $location[0]['id']
                ));
            }

            return $this->redirectToRoute("customerForInstitutionlocation", array(
                "locationid" => $location[0]['id']
            ));
        }

        /** @var User $user */
        $user = $this->getUser();
        if ($user->hasInstitutionBySid($synergyid) === 0 && !$user->isAdmin()) {
            throw new \Exception($user->getName() . " [" . $user->getEmail() . "] is not allowed to view school with synergy: " . $synergyid);
        }

        return $this->render('particulier/customerdata.html.twig', array(
            "data" => $customers,
            "locationid" => $locationId,
            'bulkmoveForm' => $bulkmoveForm->createView()
        ));
    }

    /**
     * @Route("customer/deletedevice/{did}", name="removeCustomerFromDevice")
     */
    public function removeCustomerFromDevice(Request $request): Response
    {
        $did = $request->get('did');
        //$cid = $request->get()
        $this->api->updateCustomerOnDevice($did);
        return $this->redirectToRoute('invetoryDeviceDetails', array(
            'id' => $did
        ));
    }

    /**
     * @Route("customer/deleteextradevice/{did}", name="removeCustomerFromExtraDevice")
     */
    public function removeCustomerFromExtraDevice(Request $request): Response
    {
        $exid = $request->get('did');
        $this->api->updateCustomerOnExtraDevice($exid);

        return $this->redirectToRoute('invetoryExtraDeviceDetails', array(
            'id' => $exid
        ));
    }

    /**
     * @Route("/customer/details/{cid}", name="customerdetails")
     */
    public function customerDetails(Request $request): Response
    {
        $cid = $request->get("cid");
        $customer = $this->api->getCustomerForId($cid);
        $parents = $this->api->getParentsForCustomer($cid);

        /** @var User $user */
        $user = $this->getUser();
        if ($user->hasInstitutionBySid($customer[0]['synergyid']) === 0 && !$user->isAdmin()) {
            throw new \Exception();
        }

        $moves = $this->mclr->findByCustomer($cid);
        //get devices for this customer
        $devices = $this->api->getDevicesForCustomer($cid);
        $extradevices = $this->api->getExtraDevicesForCustomer($cid);

        $schoolid = ($customer[0]['school_id']) ? $customer[0]['school_id'] : $customer[0]['school_id'];

        // DeviceSelectionForm
        $deviceForm = $this->createForm(DeviceSelectionType::class, null, array(
            'schoollocationId' => $customer[0]['schoollocation_id'],
        ));
        $deviceForm->handleRequest($request);

        if ($deviceForm->isSubmitted() && $deviceForm->isValid()) {
            $this->api->updateCustomerOnDevice($deviceForm->getData()['device'], $cid);
            return $this->redirectToRoute('customerdetails', array(
                'cid' => $cid,
            ));
        }

        // MoveCustomerFromSchoollocationForm
        $moveForm = $this->createForm(MoveCustomerLogType::class, null, array(
            'school_id' => $schoolid
        ));
        $moveForm->handleRequest($request);

        if ($moveForm->isSubmitted() && $moveForm->isValid()) {
            $mcl = new MoveCustomerLog();
            $newloc = $this->api->getSchoollocation($moveForm->get('toLocationName')->getData());

            $mcl->setCustomerId($moveForm->get('customerId')->getData());
            $mcl->setCustomerName($moveForm->get('customerName')->getData());
            $mcl->setFromLocationId($moveForm->get('fromLocationId')->getData());
            $mcl->setFromLocationName($moveForm->get('fromLocationName')->getData());
            $mcl->setToLocationId($newloc[0]['id']);
            $mcl->setToLocationName($newloc[0]['institutionName']);
            $mcl->setWhenMoved($moveForm->get('whenMoved')->getData());
            $mcl->setUser($this->getUser());

            $this->api->updateLocationOnCustomer($cid, $newloc[0]['id']);

            $this->em->persist($mcl);
            $this->em->flush();

            // move devices if any AND when the user allows it.
            if (!empty($devices) && $moveForm->get('moveDevice')->getData() == true) {
                foreach ($devices as $device) {
                    /** @var MoveDeviceLog */
                    $mdl = new MoveDeviceLog();
                    $mdl->setDeviceId($device['id']);
                    $mdl->setDeviceSerial($device['serialnumber']);
                    $mdl->setFromLocationId($moveForm->get('fromLocationId')->getData());
                    $mdl->setFromLocationName($moveForm->get('fromLocationName')->getData());
                    $mdl->setToLocationId($newloc[0]['id']);
                    $mdl->setToLocationName($newloc[0]['institutionName']);
                    $mdl->setWhenMoved($moveForm->get('whenMoved')->getData());
                    $mdl->setUser($this->getUser());

                    $this->api->updateLocationOnDevice($device['id'], $newloc[0]['id']);

                    $this->em->persist($mdl);
                    $this->em->flush();
                }
            }

            return $this->redirectToRoute('customerdetails', array(
                'cid' => $cid,
            ));
        } else {
            $moveForm->get('customerId')->setData($cid);
            $moveForm->get('customerName')->setData($customer[0]['firstname'] . " " . $customer[0]['lastname']);
            $moveForm->get('fromLocationId')->setData($customer[0]['schoollocation_id']);
            $moveForm->get('fromLocationName')->setData($customer[0]['institutionName']);
        }

        // EditCustomer form
        $editForm = $this->createForm(CustomerType::class, null, array(
            'freefieldtag01' => $customer[0]['freefieldtag01'],
            'freefieldtag02' => $customer[0]['freefieldtag02'],
            'freefieldtag03' => $customer[0]['freefieldtag03'],
            'freefieldtag04' => $customer[0]['freefieldtag04'],
            'freefieldtag05' => $customer[0]['freefieldtag05'],
        ));
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $freefields = array();
            if ($customer[0]['freefieldtag01']) {
                $freefields['freefieldtag01'] = $editForm->get('freefieldtag01')->getData();
            }
            if ($customer[0]['freefieldtag02']) {
                $freefields['freefieldtag02'] = $editForm->get('freefieldtag02')->getData();
            }
            if ($customer[0]['freefieldtag03']) {
                $freefields['freefieldtag03'] = $editForm->get('freefieldtag03')->getData();
            }
            if ($customer[0]['freefieldtag04']) {
                $freefields['freefieldtag04'] = $editForm->get('freefieldtag04')->getData();
            }
            if ($customer[0]['freefieldtag05']) {
                $freefields['freefieldtag05'] = $editForm->get('freefieldtag05')->getData();
            }
            $this->api->updateCustomer(
                $cid,
                $editForm->get('firstname')->getData(),
                $editForm->get('lastname')->getData(),
                $editForm->get('email')->getData(),
                $editForm->get('type')->getData(),
                $freefields
            );
            return $this->redirectToRoute('customerdetails', array(
                'cid' => $cid,
            ));
        } else {
            $editForm->get('lastname')->setData($customer[0]['lastname']);
            $editForm->get('firstname')->setData($customer[0]['firstname']);
            $editForm->get('email')->setData($customer[0]['email']);
            $editForm->get('type')->setData($customer[0]['type']);
            if ($customer[0]['freefieldtag01']) {
                $editForm->get('freefieldtag01')->setData($customer[0]['freefieldvalue01']);
            }
            if ($customer[0]['freefieldtag02']) {
                $editForm->get('freefieldtag02')->setData($customer[0]['freefieldvalue02']);
            }
            if ($customer[0]['freefieldtag03']) {
                $editForm->get('freefieldtag03')->setData($customer[0]['freefieldvalue03']);
            }
            if ($customer[0]['freefieldtag04']) {
                $editForm->get('freefieldtag04')->setData($customer[0]['freefieldvalue04']);
            }
            if ($customer[0]['freefieldtag05']) {
                $editForm->get('freefieldtag05')->setData($customer[0]['freefieldvalue05']);
            }
        }

        // movecustomerFromInstitution
        $moveCustomerInstitution = null;

        if ($user->isAdmin()) {
            $moveCustomerInstitution = $this->createForm(MoveCustomerFromInstitutionType::class);
            $moveCustomerInstitution->handleRequest($request);
            if ($moveCustomerInstitution->isSubmitted() && $moveCustomerInstitution->isValid()) {
                /** @var Institution $inst */
                $inst = $moveCustomerInstitution->get('institutions')->getData()['institutions'];
                $schoolId = $this->api->getSchoolForSynergy($inst->getSynergyId());
                if ($moveCustomerInstitution->get('withBYOD')->getData()) {
                    //$devices = $this->api->getDevicesForCustomer($cid);
                    foreach ($devices as $device) {
                        $this->api->moveDeviceToSchool($device['id'], $cid, $schoolId[0]);
                    }
                }
                if ($moveCustomerInstitution->get('withExtra')->getData()) {
                    //$devices = $this->api->getExtraDevicesForCustomer($cid);
                    foreach ($extradevices as $exdevice) {
                        $this->api->moveExtraDeviceToSchool($exdevice['id'], $cid, $schoolId[0]);
                    }
                }
                $this->api->moveCustomerToSchool($cid, $schoolId[0]);
                return $this->redirectToRoute('customerdetails', array(
                    'cid' => $cid,
                ));
            }
        }

        // addContactPersonForm
        $addContactPerson = $this->createForm(ParentType::class);
        $addContactPerson->handleRequest($request);
        if ($addContactPerson->isSubmitted() && $addContactPerson->isValid()) {
            $contact = new ContactPerson();
            $contact->setFirstname($addContactPerson->get('firstname')->getData());
            $contact->setLastname($addContactPerson->get('lastname')->getData());
            $contact->setEmail($addContactPerson->get('email')->getData());
            $contact->setPhone(($addContactPerson->get('phone')->getData()) ? $addContactPerson->get('phone')->getData() : '');
            $this->api->addParent($cid, $contact->toJSON());
            return $this->redirectToRoute('customerdetails', array(
                'cid' => $cid,
            ));
        }

        return $this->render('particulier/customerdetails.html.twig', array(
            "customer" => $customer[0],
            "moveForm" => $moveForm->createView(),
            "moves" => $moves,
            "devices" => $devices,
            "extradevices" => $extradevices,
            "deviceForm" => $deviceForm->createView(),
            "editForm" => $editForm->createView(),
            "cid" => $cid,
            "moveInstitution" => $moveCustomerInstitution === null ? null : $moveCustomerInstitution->createView(),
            "parents" => $parents,
            "addParentForm" => $addContactPerson->createView()
        ));
    }

    /**
     * @Route("/customer/details/{cid}/remove/{pid}", name="removeparent")
     */
    public function removeParentForCustomer(Request $request): Response
    {
        $cid = $request->get('cid');
        $pid = $request->get('pid');

        $this->api->removeParent($cid, $pid);

        return $this->redirectToRoute('customerdetails', array(
            'cid' => $cid,
        ));
    }
}
