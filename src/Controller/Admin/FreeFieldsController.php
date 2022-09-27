<?php

namespace App\Controller\Admin;

use App\Entity\Institution;
use App\Form\FreeFieldsType;
use App\Repository\InstitutionRepository;
use App\Service\SP2ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class FreeFieldsController extends AbstractController
{
    private $api;
    private $ir;

    public function __construct(SP2ApiService $api, InstitutionRepository $ir)
    {
        $this->api = $api;
        $this->ir = $ir;
    }

    /**
     * @Route("/free/fields", name="admin_free_fields")
     */
    public function index(Request $request): Response
    {
        $cookies = $request->cookies;
        if ($cookies->has('institution_id') && $cookies->get('institution_id') !== '') {
            /** @var Institution $institution */
            $institution = $this->ir->find($cookies->get('institution_id'));
            if (!$institution) {
                throw new \Exception("institution not found");
            }
            $sp2School = $this->api->getSchoolForSynergy($institution->getSynergyId());
            //$schoolId[0]
            $form = $this->createForm(FreeFieldsType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->get('model')->getData() === 'm4s_devices') {
                    $this->api->freeFieldForDevicesOnSchool(
                        $sp2School[0],
                        'add',
                        $form->get('name')->getData(),
                        $form->get('defaultvalue')->getData()
                    );
                } elseif ($form->get('model')->getData() === 'm4s_customer') {
                    $this->api->freeFieldForCustomerOnSchool(
                        $sp2School[0],
                        'add',
                        $form->get('name')->getData(),
                        $form->get('defaultvalue')->getData()
                    );
                }
                $this->redirectToRoute('admin_free_fields');
            }
            $devices = $this->api->freeFieldForDevicesOnSchool($sp2School[0], 'get');
            $customers = $this->api->freeFieldForCustomerOnSchool($sp2School[0], 'get');
            return $this->render('admin/free_fields/index.html.twig', [
                'form' => $form->createView(),
                'devices' => empty($devices) ? array() : $devices[0],
                'customers' => empty($customers) ? array() : $customers[0],
                'schoolid' => $sp2School[0]
            ]);
        } else {
            $this->addFlash("info", "select an Institution first");
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/free/fields/removefordevice", name="admin_free_fields_devices_remove")
     */
    public function deleteTagForDevice(Request $request): Response
    {
        $tag = $request->get('tagname');
        $school = $request->get('schoolid');
        $this->api->freeFieldForDevicesOnSchool($school, 'delete', $tag);
        return $this->redirectToRoute('admin_free_fields');
    }

    /**
     * @Route("/free/fields/removeforcustomer", name="admin_free_fields_customers_remove")
     */
    public function deleteTagForCustomer(Request $request): Response
    {
        $tag = $request->get('tagname');
        $school = $request->get('schoolid');
        $this->api->freeFieldForCustomerOnSchool($school, 'delete', $tag);
        return $this->redirectToRoute('admin_free_fields');
    }
}
