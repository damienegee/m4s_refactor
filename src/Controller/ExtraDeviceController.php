<?php

namespace App\Controller;

use App\Entity\ExtraDevice;
use App\Form\ExtraDeviceType;
use App\Service\SP2ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExtraDeviceController extends AbstractController
{
    private $api;

    public function __construct(SP2ApiService $api)
    {
        $this->api = $api;
    }

    /**
     * @Route("/extra/device", name="extra_device")
     */
    public function index(): Response
    {
        return $this->render('extra_device/index.html.twig', [
            'controller_name' => 'ExtraDeviceController',
        ]);
    }

    /**
     * @Route("/inventory/assignextradevicetolocation", name="assignextradevicetolocation")
     */
    public function assigndevicetolocation(Request $request): Response
    {
        $counter = 0;
        $synergy = $request->get('synergy');
        $locationId = $request->request->get('location');
        foreach ($request->request->all() as $key => $value) {
            if (is_numeric($key)) {
                // $this->api->updateLocationOnDevice($key, $locationId);
                $this->api->updateLocationOnExtraDevice($key, $locationId);
                $counter++;
            }
        }
        $this->addFlash('success', $counter . " toestellen verhuisd");
        return $this->redirectToRoute('inventoryForInstitution', array(
            "synergyid" => $synergy
        ));
    }

    /**
     * @Route("/extra/device/location/add", name="addExtraDeviceForLocation")
     */
    public function addExtraDeviceForLocation(Request $request): Response
    {
        $locationId = $request->get('locationid');
        $schoollocation = $this->api->getSchoollocation($locationId);
        $form = $this->createForm(ExtraDeviceType::class, null, array(
            'schoollocationId' => $locationId
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $exd = new ExtraDevice();
            $exd->setM4sSchoollocationId($form->get('m4sSchoollocationId')->getData());
            $exd->setProductnumber($form->get('productnumber')->getData());
            $exd->setManufacturer($form->get('manufacturer')->getData());
            $exd->setModel($form->get('model')->getData());
            $exd->setSupplier($form->get('supplier')->getData());
            $exd->setSerialNumber($form->get('serialNumber')->getData());

            $result = $this->api->addExtraDeviceForSchoollocation($locationId, $exd->toJSON());
            if ($result) {
                return $this->redirectToRoute('inventoryForInsitutionlocation', array(
                    "locationid" => $locationId
                ));
            }
        } else {
            $form->get('m4sSchoollocationId')->setData($schoollocation[0]['id']);
        }

        return $this->render('extra_device/form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/inventory/extradevicedetails/{id}/remove", name="removeExtraDeviceDetails")
     */
    public function removeExtraDevice(Request $request)
    {
        $exid = $request->get('id');
        $this->api->updateCustomerOnExtraDevice($exid);
        $this->api->removeExtraDevice($exid);

        $this->addFlash("info", "Device has been deleted");
        return $this->redirectToRoute('inventory');
    }
}
