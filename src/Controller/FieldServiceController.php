<?php

namespace App\Controller;

use App\Entity\Institution;
use App\Entity\Ticket;
use App\Entity\User;
use App\Form\InteralTicketStateType;
use App\Form\TicketType;
use App\Repository\InstitutionRepository;
use App\Service\SP2ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FieldServiceController extends AbstractController
{

    private $api;
    private $ir;
    private $client;

    public function __construct(SP2ApiService $api, InstitutionRepository $ir, HttpClientInterface $client)
    {
        $this->api = $api;
        $this->ir = $ir;
        $this->client = $client;
    }
    /**
     * @Route("/field/service", name="field_service")
     */
    public function index(Request $request): Response
    {
        $cookies = $request->cookies;
        if ($cookies->has('institution_id') && $cookies->get('institution_id') != '') {
            return $this->redirectToRoute('field_service_for_institution', [
                "id" => $cookies->get('institution_id')
            ]);
        } else if ($cookies->has('location_id') && $cookies->get('location_id') != "") {
            return $this->redirectToRoute('fieldserviceforlocation', [
                "locationid" => $cookies->get('location_id')
            ]);
        }
        $this->addFlash("info", "select an Institution first");
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/field/service/{id}", name="field_service_for_institution")
     */
    public function fieldServiceForInstitution(Request $request): Response
    {
        $id = $request->get('id');
        $openIssues = array();
        $wait =  array();
        $closed = array();

        /** @var User */
        $user = $this->getUser();
        /** @var Institution $institution */
        $institution = $this->ir->find($id);

        if (!$institution) {
            throw new \Exception('Institution was not found');
        }

        if ($institution->containsUser($user) || in_array('ROLE_ADMIN', $user->getRoles())) {
            $fieldServices = $this->api->getFieldServicesForInstitution($institution->getSynergyId());
//            dd($fieldServices);
            foreach ($fieldServices as $fieldservive) {
                if ($fieldservive['status'] === 'Hersteld' || $fieldservive['status'] === 'Afgesloten' ||  $fieldservive['status'] === 'Afgekeurd') {
                    array_push($closed, $fieldservive);
                } elseif ($fieldservive['status'] === 'Approval van forfait prijs' || $fieldservive['status'] === 'Approval van offerte') {
                    array_push($wait, $fieldservive);
                } else {
                    array_push($openIssues, $fieldservive);
                }
            }

            return $this->render('field_service/index.html.twig', [
                'open' => $openIssues,
                'closed' => $closed,
                'wait' => $wait,
                'institution' => $institution,
                'data' => $fieldServices,
                'synergy' => $institution->getSynergyId()
            ]);
        } else {
            $this->addFlash("info", "select an Institution first");
            return $this->redirectToRoute('home');
        }
    }

    // public function fieldServiceDetail(Request $request): Response
    // {
    //     /** @var User $user */
    //     $user = $this->getUser();
    //     $fieldServiceId = $request->get('fieldservice_id');
    //     $fieldService = $this->api->getFieldServiceDetails($fieldServiceId, $user->getLocale());
    //     return $this->render('field_service/_field_service_details.html.twig', [
    //         'data' => $fieldService
    //     ]);
    // }

    /**
     * @Route("/fieldservice/{locationid}", name="fieldserviceforlocation")
     */
    public function fieldServiceForLocation(Request $request): Response
    {
        $locid = $request->get("locationid");

        $location = $this->api->getSchoollocation($locid);
        $synergy = $this->api->getSPSynergyBySchoolM4SId($location[0]['school_id']);

        $institution = $this->ir->findInstitutionBySynergy($synergy);

        if (!$institution) {
            throw new \Exception("institution not found with synergy " . $synergy);
        }

        return $this->redirectToRoute("field_service_for_institution", array(
            "id" => $institution->getId()
        ));
    }

    public function fieldServiceGraphAction(Request $request): Response
    {
        $synergy = $request->get('synergy');
        $sp2Url = $this->getParameter('app.sp2.url');

        /** @var User */
        $user = $this->getUser();
        /** @var Institution $institution */
        $institution = $this->ir->findInstitutionBySynergy($synergy);


        if (!$institution) {
            throw new \Exception("Institution with Synergy " . $synergy . " was not found");
        }

        if ($institution->containsUser($user) || in_array('ROLE_ADMIN', $user->getRoles())) {
            $response = $this->client->request(
                'GET',
                $sp2Url . '/fieldServiceDashboardBySynergy.php',
                array(
                    'query' => array(
                        'synergyid' => $synergy
                    )
                )
            );
            $content = $response->getContent();
            return new Response($content);
        } else {
            $this->addFlash("info", "select an Institution first");
            return $this->redirectToRoute('home');
        }
    }

    public function createFieldServiceForDeviceAction(Request $request): Response
    {
        $sp2Url = $this->getParameter('app.sp2.url');
        $serial = $request->get('serialnumber');
        $location = $request->get('location');
        /** @var User */
        $user = $this->getUser();
        $lang = $user->getLocale();
        $response = $this->client->request(
            'GET',
            $sp2Url . '/fieldServices/createFieldServiceTicket.php',
            array(
                'query' => array(
                    'serial' => $serial,
                    'location' => $location,
                    'lang' => $lang
                )
            )
        );
        $content = $response->getContent();
        return new Response($content);
    }

    public function createFieldServiceForLocationAction(Request $request): Response
    {
        $sp2Url = $this->getParameter('app.sp2.url');
        $synergy = $request->get('synergy');
        /** @var User */
        $user = $this->getUser();
        $lang = $user->getLocale();

        /** @var Institution $institution */
        $institution = $this->ir->findInstitutionBySynergy($synergy);

        if (!$institution) {
            throw new \Exception("Institution with Synergy " . $synergy . " was not found");
        }

        if ($institution->containsUser($user) || in_array('ROLE_ADMIN', $user->getRoles())) {
            $response = $this->client->request(
                'GET',
                $sp2Url . '/fieldServices/createFieldServiceTicket.php',
                array(
                    'query' => array(
                        'synergyid' => $synergy,
                        'lang' => $lang
                    )
                )
            );
            $content = $response->getContent();
            return new Response($content);
        } else {
            $this->addFlash("info", "select an Institution first");
            return $this->redirectToRoute('home');
        }
    }
}
