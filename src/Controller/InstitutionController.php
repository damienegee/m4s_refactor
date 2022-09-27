<?php

namespace App\Controller;

use App\Entity\Institution;
use App\Entity\User;
use App\Repository\InstitutionRepository;
use App\Service\SP2ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class InstitutionController extends AbstractController
{
    private $api;
    private $ir;

    public function __construct(SP2ApiService $api, InstitutionRepository $ir)
    {
        $this->api = $api;
        $this->ir = $ir;
    }
    public function myInstitutions(): Response
    {
        /** @var User */
        $user = $this->getUser();

        return $this->render('institution/myInstitutions.html.twig', array(
            "myInstitutions" => $user->getInstitutions()
        ));
    }

    /**
     * @Route("/institution/{id}", name="institutionDetail")
     */
    public function instutionDetail(Request $request, PaginatorInterface $paginator): Response
    {
        $id = $request->get('id');
        /** @var User */
        $user = $this->getUser();

        $institution = $this->ir->find($id);

        if (!$institution) {
            throw new \Exception("Institution with ID " . $id . " was not found");
        }


        $inventoryCounter = $this->api->getDashboardCounterForSchool($institution->getSynergyId(), 'inventory');

        $clientsWithoutDeviceCounter = $this->api->getDashboardCounterForSchool($institution->getSynergyId(), 'clientsWithouDevices');
        $clientsCounter = $this->api->getDashboardCounterForSchool($institution->getSynergyId(), 'clients');
        $schoollocations = $this->api->getSchoollocationsForSchool($institution->getSynergyId());

        if ($institution->containsUser($user) || in_array('ROLE_ADMIN', $user->getRoles())) {
            $openEvents = $this->institutionOpenEventAction($institution->getSynergyId());

            $closedEvents = $this->institutionClosedEventAction($institution->getSynergyId());

            return $this->render("institution/institutionDetail.html.twig", [
                'institution' => $institution,
                "inventoryCounter" => (count($inventoryCounter) > 0) ? $inventoryCounter[0] : 0,
                "openFieldServices" => $openEvents,
                'closedFieldServices' => $closedEvents,
                "clientWithoutDeviceCounter" => (count($clientsWithoutDeviceCounter) > 0) ? $clientsWithoutDeviceCounter[0] : 0,
                "clientsCounter" => (count($clientsCounter) > 0) ? $clientsCounter[0] : 0,
                "schoollocations" => (!empty($schoollocations)) ? $schoollocations : array(),
                "iid" => $id
            ]);
        } else {
            $this->addFlash("info", "select an Institution first");
            return $this->redirectToRoute('home');
        }
    }

    public function instutionDetailAction(Request $request): Response
    {
        $id = $request->get('id');

        /** @var User */
        $user = $this->getUser();

        /** @var Institution $institution */
        $institution = $this->ir->find($id);
        // $cachKey = 'InstitutionDetail' . $institution->getSynergyId();
        if (!$institution) {
            throw new \Exception("Institution with ID " . $id . " was not found");
        }

        if ($institution->containsUser($user) || in_array('ROLE_ADMIN', $user->getRoles())) {
            $inventoryCounter = $this->api->getDashboardCounterForSchool($institution->getSynergyId(), 'inventory');
            $openEventsCounter = $this->api->getDashboardCounterForSchool($institution->getSynergyId(), 'fieldservice');
            $clientsWithoutDeviceCounter = $this->api->getDashboardCounterForSchool($institution->getSynergyId(), 'clientsWithouDevices');
            $clientsCounter = $this->api->getDashboardCounterForSchool($institution->getSynergyId(), 'clients');

            return $this->render("institution/_institutionCounter.html.twig", [
                'institution' => $institution,
                "inventoryCounter" => (count($inventoryCounter) > 0) ? $inventoryCounter[0] : 0,
                "openEventsCounter" => (count($openEventsCounter) > 0) ? $openEventsCounter[0] : 0,
                "clientWithoutDeviceCounter" => (count($clientsWithoutDeviceCounter) > 0) ? $clientsWithoutDeviceCounter[0] : 0,
                "clientsCounter" => (count($clientsCounter) > 0) ? $clientsCounter[0] : 0,
            ]);
        } else {
            return $this->render('home/accessdenied.html.twig');
        }
    }

    private function institutionOpenEventAction($synergy): array
    {
        $fieldServices = $this->api->getFieldServicesForInstitution($synergy);

        $openServices = array();

        foreach ($fieldServices as $service) {
            if (
                $service['status'] !== 'Hersteld' &&
                $service['status'] !== 'Afgesloten' &&
                $service['status'] !== 'Afgekeurd' &&
                $service['status'] !== 'Approval van forfait prijs' &&
                $service['status'] !== 'Approval van offerte'
            ) {
                array_push($openServices, $service);
            }
        }

        return $openServices;
    }

    private function institutionClosedEventAction($synergy): array
    {
        $fieldServices = $this->api->getFieldServicesForInstitution($synergy);

        $closedServices = array();

        foreach ($fieldServices as $service) {
            if ($service['status'] === 'Hersteld' || $service['status'] === 'Afgesloten' ||  $service['status'] === 'Afgekeurd') {
                array_push($closedServices, $service);
            }
        }

        return $closedServices;
    }
}
