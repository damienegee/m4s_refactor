<?php

namespace App\Controller;

use App\Entity\Institution;
use App\Repository\InstitutionRepository;
use App\Service\SP2ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForecastController extends AbstractController
{
    private $api;
    private $ir;

    public function __construct(SP2ApiService $api, InstitutionRepository $ir)
    {
        $this->api = $api;
        $this->ir = $ir;
    }

    /**
     * @Route("/forecast", name="forecast")
     */
    public function index(Request $request): Response
    {
        $cookies = $request->cookies;
        $academicYear = null;
        $schoolyear = null;
        $forecasts = array();
        $hiresDetails = array();
        $webshopDetails = array();

        if ($cookies->has('academic_year')) {
            $academicYear = substr($cookies->get('academic_year'), -4);
            $firstpart = substr($cookies->get('academic_year'), 0, 4);
            $schoolyear = substr($firstpart, -2) . substr($academicYear, -2);
        }

        if ($cookies->has('institution_id') && $cookies->get('institution_id') !== '') {
            $iid = $cookies->get('institution_id');
            /** @var Institution $institution */
            $institution = $this->getInstution($iid);
        } elseif ($cookies->has('location_id') && $cookies->get('location_id') !== '') {
            $sp2Schoollocation = $this->api->getSchoollocation($cookies->get('location_id'));
            $institution = $this->getInstitutionBySynergy($sp2Schoollocation[0]['synergyid']);
        } else {
            $this->addFlash("info", "select an Institution first");
            return $this->redirectToRoute('home');
        }

        if (!$institution) {
            throw new \Exception("Institution not found for ID: " . $cookies->get('institution_id'));
        }

        $forecasts['forecasts'] = $this->api->getForecasts($institution->getSynergyId(), $academicYear - 1);
        $forecasts['hires'] = $this->api->getLeermiddel($institution->getSynergyId(), $schoolyear);
        $forecasts['hiresDetails'] = $this->api->getLeermiddelDetails($institution->getSynergyId(), $schoolyear);
        $forecasts['webshops'] = $this->api->getWebshop($institution->getSynergyId(), $academicYear);
        $forecasts['webshopDetails'] = $this->api->getWebshopDetails($institution->getSynergyId(), $academicYear);

        foreach ($forecasts['hiresDetails'] as $key => $details) {
            $hiresDetails[$key]['VoornaamLeerling'] =  $details['VoornaamLeerling'];
            $hiresDetails[$key]['NaamLeerling'] = $details['NaamLeerling'];
            $hiresDetails[$key]['ContractVolgnummer'] =  $details['ContractVolgnummer'];
            $hiresDetails[$key]['DatumContract'] = $details['DatumContractopgemaakt'];
            $hiresDetails[$key]['VoorschotOntvangen'] = $details['VoorschotOntvangen'];
            $hiresDetails[$key]['MethodeContract'] = $details['MethodeContractOntvangen'];
            $hiresDetails[$key]['DatumOntvangen'] = $details['DatumContractOntvangen'];
            $hiresDetails[$key]['MethodeBetaald'] = $details['MethodeVoorschotBetaald'];
            $hiresDetails[$key]['DatumBetaald'] = $details['DatumVoorschotOntvangen'];
            $hiresDetails[$key]['NaamToestel'] = $details['NaamToestel'];
            $hiresDetails[$key]['OmschrijvingToestel'] = $details['OmschrijvingToestel'];
            $hiresDetails[$key]['UniqueIdentifier'] = $details['UniqueIdentifier'];
            $hiresDetails[$key]['splabel'] = $details['instruction'];
            $hiresDetails[$key]['Leerjaar'] =  $details['Leerjaar'];
            $hiresDetails[$key]['Waarborg'] =  $details['Waarborg'];
            $hiresDetails[$key]['NaamOuder'] =  $details['NaamOuder'];
            $hiresDetails[$key]['NaamOuder2'] =  $details['NaamOuder2'];
            $hiresDetails[$key]['StraatNr'] =  $details['StraatNr'];
            $hiresDetails[$key]['Postcode'] =  $details['Postcode'];
            $hiresDetails[$key]['Gemeente'] =  $details['Gemeente'];
            $hiresDetails[$key]['Land'] =  $details['Land'];
            $hiresDetails[$key]['Tel1'] =  $details['Tel1'];
            $hiresDetails[$key]['Tel2'] =  $details['Tel2'];
            $hiresDetails[$key]['Email1'] =  $details['Email1'];
            $hiresDetails[$key]['Email2'] =  $details['Email2'];
            $hiresDetails[$key]['label'] =  $details['label'];
            $hiresDetails[$key]['type'] = 'H';
            $hiresDetails[$key]['betalingok'] = null;
            $hiresDetails[$key]['status'] = null;
            $hiresDetails[$key]['Prijs'] =  $details['HuurPrijs'];
            $hiresDetails[$key]['Termijn'] =  $details['Huurtermijn'];
        }

        foreach ($forecasts['webshopDetails'] as $key => $details) {
            $webshopDetails[$key]['VoornaamLeerling'] =  $details['voornaam_student'];
            $webshopDetails[$key]['NaamLeerling'] =  $details['naam_student'];
            $webshopDetails[$key]['ContractVolgnummer'] =  $details['increment_id'];
            $webshopDetails[$key]['DatumContract'] = $details['created_at'];
            $webshopDetails[$key]['VoorschotOntvangen'] = $details['betalingok'];
            $webshopDetails[$key]['MethodeContract'] = NULL;
            $webshopDetails[$key]['DatumOntvangen'] = NULL;
            $webshopDetails[$key]['MethodeBetaald'] = NULL;
            $webshopDetails[$key]['DatumBetaald'] = $details['payment_date'];
            $webshopDetails[$key]['NaamToestel'] = $details['name'];
            $webshopDetails[$key]['OmschrijvingToestel'] = NULL;
            $webshopDetails[$key]['UniqueIdentifier'] = NULL;
            $webshopDetails[$key]['splabel'] = NULL;
            $webshopDetails[$key]['Leerjaar'] =  $details['leerjaar'];
            $webshopDetails[$key]['Waarborg'] =  NULL;
            $webshopDetails[$key]['NaamOuder'] =  $details['customer_firstname'] . ' ' . $details['customer_lastname'];
            $webshopDetails[$key]['NaamOuder2'] =  NULL;
            $webshopDetails[$key]['StraatNr'] =  $details['street'];
            $webshopDetails[$key]['Postcode'] =  $details['postcode'];
            $webshopDetails[$key]['Gemeente'] =  $details['city'];
            $webshopDetails[$key]['Land'] =  NULL;
            $webshopDetails[$key]['Tel1'] =  $details['telephone'];
            $webshopDetails[$key]['Tel2'] =  NULL;
            $webshopDetails[$key]['Email1'] =  $details['customer_email'];
            $webshopDetails[$key]['Email2'] =  NULL;
            $webshopDetails[$key]['label'] =  $details['label'];
            $webshopDetails[$key]['type'] = 'W';
            $webshopDetails[$key]['betalingok'] = $details['betalingok'];
            $webshopDetails[$key]['status'] = $details['status'];
            $webshopDetails[$key]['Prijs'] =  $details['price'];
            $webshopDetails[$key]['Termijn'] =  null;
        }

        $data = array(...$hiresDetails, ...$webshopDetails);

        return $this->render('forecast/index.html.twig', array(
            'forecasts' => $forecasts['forecasts'],
            'hires' => $forecasts['hires'],
            'webshops' => $forecasts['webshops'],
            'data' => $data
        ));
    }

    private function getInstution($iid): Institution
    {
        $ret = null;

        /** @var Institution $institution */
        $institution = $this->ir->find($iid);

        if ($this->checkInstitution($institution)) {
            $ret = $institution;
        }

        return $ret;
    }

    private function getInstitutionBySynergy($synergy): Institution
    {
        $ret = null;

        /** @var Institution $institution */
        $institution = $this->ir->findInstitutionBySynergy($synergy);

        if ($this->checkInstitution($institution)) {
            $ret = $institution;
        }

        return $ret;
    }

    private function checkInstitution(Institution $institution)
    {
        $ret = false;

        if (!$institution) {
            // throw new \Exception("institution not found");
            $ret = false;
        }

        if ($institution->containsUser($this->getUser()) || in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            $ret = true;
        }

        return $ret;
    }
}
