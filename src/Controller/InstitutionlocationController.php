<?php

namespace App\Controller;

use App\Form\AddSchoollocationType;
use App\Service\SP2ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\InstitutionLocation;
use App\Entity\User;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class InstitutionlocationController extends AbstractController
{
    private $api;

    public function __construct(SP2ApiService $api)
    {
        $this->api = $api;
    }
    /**
     * @Route("/institutionlocation", name="institutionlocation")
     */
    public function index(): Response
    {
        return $this->render('institutionlocation/index.html.twig', [
            'controller_name' => 'InstitutionlocationController',
        ]);
    }

    public function instutionlocationCounterAction(Request $request): Response
    {
        $sp2LocationId = $request->get('locId');
        $locSize = $request->get('locsize');
        $inventoryCounter = $this->api->getDashboardCounterForSchoolLocation($sp2LocationId, 'inventory');
        $clientsWithoutDeviceCounter = $this->api->getDashboardCounterForSchoolLocation($sp2LocationId, 'clientsWithouDevices');
        $clientsCounter = $this->api->getDashboardCounterForSchoolLocation($sp2LocationId, 'clients');
        // dd($clientsWithoutDeviceCounter);

        return $this->render("institutionlocation/_institutionlocationCounter.html.twig", [
            "inventoryCounter" => (count($inventoryCounter) > 0) ? $inventoryCounter[0] : 0,
            "clientWithoutDeviceCounter" => (count($clientsWithoutDeviceCounter) > 0) ? $clientsWithoutDeviceCounter[0]['counter'] : 0,
            "clientsCounter" => (count($clientsCounter) > 0) ? $clientsCounter[0] : 0,
            "m4sLocationId" => $sp2LocationId,
            "locSize" => $locSize
        ]);
    }

    /**
     * @Route("institutionlocation/add", name="addinstitutionlocation")
     */
    public function addInstituionlocation(Request $request)
    {
        $synergyId = $request->get('synergy');
        $iid = $request->get('sid');

        $form = $this->createForm(AddSchoollocationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var InstitutionLocation $location */
            $location = $form->getData();

            $result = $this->api->addSchoollocationForSchool($synergyId, $location->toJSON());

            if ($result) {
                return $this->redirectToRoute('institutionDetail', array(
                    'id' => $iid
                ));
            }
        }

        return $this->render("institutionlocation/form.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("institutionlocation/{lid}", name="institutionlocationForId")
     */
    public function instituionlocationDetails(Request $request, PaginatorInterface $paginator): Response
    {
        $sp2LocationId = $request->get('lid');
        // $cachKey = "locationDetails" . $sp2LocationId;
        $customers = $this->api->getCustomerForInstitutionLocation($sp2LocationId);
        $devices = $this->api->getDevicesForInstitutionLocation($sp2LocationId);
        $schoollocation = $this->api->getSchoollocation($sp2LocationId);

        /** @var User $user */
        $user = $this->getUser();
        if ($user->hasInstitutionBySid($schoollocation[0]['synergyid']) === 0 && !$user->isAdmin()) {
            throw new \Exception();
        }

        $allCustomers = $paginator->paginate(
            $customers,
            $request->query->getInt('cuspage', 1),
            16,
            array(
                'pageParameterName' => 'cuspage'
            )
        );

        $allDevices = $paginator->paginate(
            $devices,
            $request->query->getInt('devpage', 1),
            16,
            array(
                'pageParameterName' => 'devpage'
            )
        );

        return $this->render("institutionlocation/institutionlocationDetail.html.twig", [
            "schoollocation" => $schoollocation[0],
            "customers" => $allCustomers,
            "devices" => $allDevices,
        ]);
    }

    /**
     * 
     * @Route("institutionlocation/{lid}/edit", name="editlocation", methods={"GET", "POST"})
     * @param Request $request
     */
    public function editInstitutionLocation(Request $request): Response
    {
        $iid = $request->get('iid');
        $lid = $request->get('lid');
        $lname = $request->get('lname');
        $lnumber = $request->get('lnumber');
        $aid =  $request->get('aid');
        $street = $request->get('street');
        $number = $request->get('number');
        $bus =  $request->get('bus');
        $zipcode = $request->get('zipcode');
        $city = $request->get('city');

        $form = $this->createForm(AddSchoollocationType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = array();
            $data['lid'] = $lid;
            $data['aid'] = $aid;
            $data['institutionnumber'] = $form->get('institutionnumber')->getData();
            $data['institutionname'] = $form->get('institutionname')->getData();
            $data['street'] = $form->get('address')->get('street')->getData();
            $data['number'] = $form->get('address')->get('number')->getData();
            $data['bus'] = $form->get('address')->get('bus')->getData();
            $data['zipcode'] = $form->get('address')->get('zipcode')->getData();
            $data['city'] = $form->get('address')->get('city')->getData();
            $this->api->editInstitutionlocation($data);
            return $this->redirectToRoute('institutionDetail', array(
                "id" => $iid
            ));
        } else {
            $form->get('institutionnumber')->setData($lnumber);
            $form->get('institutionname')->setData($lname);
            $form->get('address')->get('street')->setData($street);
            $form->get('address')->get('number')->setData($number);
            $form->get('address')->get('bus')->setData($bus);
            $form->get('address')->get('zipcode')->setData($zipcode);
            $form->get('address')->get('city')->setData($city);
        }
        return $this->render('institutionlocation/form.html.twig', array(
            'form' => $form->createView(),
            'title' => $lname
        ));
    }
}
