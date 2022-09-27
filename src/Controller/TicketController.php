<?php

namespace App\Controller;

use App\Entity\Institution;
use App\Entity\Ticket;
use App\Entity\User;
use App\Form\InteralTicketStateType;
use App\Form\TicketDetailType;
use App\Repository\InstitutionRepository;
use App\Service\SP2ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    private $api;
    private $ir;

    public function __construct(SP2ApiService $api, InstitutionRepository $ir)
    {
        $this->api = $api;
        $this->ir = $ir;
    }

    /**
     * @Route("/ticket", name="ticket")
     */
    public function index(Request $request): Response
    {
        $cookies = $request->cookies;
        if ($cookies->has('institution_id') && $cookies->get('institution_id') != '') {
            return $this->redirectToRoute('internal_ticket_for_institution', [
                "id" => $cookies->get('institution_id')
            ]);
        } else if ($cookies->has('location_id') && $cookies->get('location_id') != "") {
            return $this->redirectToRoute('internal_ticket_for_location', [
                "locationid" => $cookies->get('location_id')
            ]);
        }
        $this->addFlash("info", "select an Institution first");
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/ticket/{id}", name="internal_ticket_for_institution")
     */
    public function internalTicketsForInstitution(Request $request): Response
    {
        $id = $request->get('id');
        $open = array();
        $inprogress =  array();
        $closed = array();
        $tickets = array();

        /** @var User */
        $user = $this->getUser();
        /** @var Institution $institution */
        $institution = $this->ir->find($id);

        if (!$institution) {
            throw new \Exception('Institution was not found');
        }

        if ($institution->containsUser($user) || in_array('ROLE_ADMIN', $user->getRoles())) {
            $tickets = $this->api->getInteralTicketForSchool($institution->getSynergyId());
            foreach ($tickets as $ticket) {
                if ($ticket['state'] === 'open') {
                    array_push($open, $ticket);
                } elseif ($ticket['state'] === 'closed') {
                    array_push($closed, $ticket);
                } elseif ($ticket['state'] === 'in progress') {
                    array_push($inprogress, $ticket);
                }
            }

            $createTicketForm = $this->createForm(TicketDetailType::class, null, array(
                'synergy' => $institution->getSynergyId()
            ));
            $createTicketForm->handleRequest($request);

            if ($createTicketForm->isSubmitted() && $createTicketForm->isValid()) {
                $device = $this->api->getDeviceDetails($createTicketForm->get('device')->getData());

                $internalTicket = new Ticket();
                $internalTicket->setCustomer($device[0]['customer_id']);
                $internalTicket->setDescription($createTicketForm->get('description')->getData());
                $internalTicket->setDevice($createTicketForm->get('device')->getData());
                $internalTicket->setProblem($createTicketForm->get('problem')->getData());
                $internalTicket->setSchoollocation($device[0]['schoollocation_id']);
                $internalTicket->setSchool($device[0]['school_id']);
                $internalTicket->setState($createTicketForm->get('state')->getData());

                $insertedId = $this->api->addInternalTicket($internalTicket->toJSON());

                $this->addFlash('info', 'new ticket created: ' . $insertedId['insertedid']);
                return $this->redirectToRoute('internal_ticket_for_institution', array(
                    'id' => $id
                ));
            }

            return $this->render('ticket/index.html.twig', array(
                'open' => $open,
                'closed' => $closed,
                'inprogress' => $inprogress,
                'data' => $tickets,
                'createTicketForm' => $createTicketForm->createView()
            ));
        } else {
            $this->addFlash("info", "select an Institution first");
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/ticket/{locationid}", name="internal_ticket_for_location")
     */
    public function internalTicketsForLocation(Request $request): Response
    {
        $locid = $request->get("locationid");

        $location = $this->api->getSchoollocation($locid);
        $synergy = $this->api->getSPSynergyBySchoolM4SId($location[0]['school_id']);

        $institution = $this->ir->findInstitutionBySynergy($synergy);

        if (!$institution) {
            throw new \Exception("institution not found with synergy " . $synergy);
        }

        return $this->redirectToRoute("internal_ticket_for_institution", array(
            "id" => $institution->getId()
        ));
    }

    /**
     * @Route("/ticket/update/{id}", name="internalticketupdate")
     */
    public function updateInteralTicketState(Request $request): Response
    {
        $tid = $request->get('id');
        /** @var Ticket $ticket */
        $ticket = $this->api->getInternalTicketsForId($tid);
        if (is_null($ticket)) {
            throw new \Exception('ticket not found');
        }
        $stateForm = $this->createForm(InteralTicketStateType::class, $ticket);
        $stateForm->handleRequest($request);
        if ($stateForm->isSubmitted() && $stateForm->isValid()) {
            $ticket['state'] = ($stateForm->get('state')->getData());
            $this->api->updateInternalTicket(json_encode($ticket));
            return $this->redirectToRoute('invetoryDeviceDetails', array(
                'id' => $ticket['device_id']
            ));
        }

        return $this->render('ticket/_internal_field_service_state_form.html.twig', array(
            'stateform' => $stateForm->createView(),
            'ticket' => $ticket
        ));
    }
}
