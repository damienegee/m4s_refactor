<?php

namespace App\Controller;

use App\Entity\Institution;
use App\Repository\InstitutionRepository;
use App\Service\BYODService;
use App\Service\SP2ApiService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BYODController extends AbstractController
{

    private $api;
    private $cache;
    private $client;
    private $byodservice;
	private $ir;

    public function __construct(SP2ApiService $api, CacheInterface $cache, HttpClientInterface $client, BYODService $byodservice, InstitutionRepository $ir)
    {
        $this->api = $api;
        $this->cache = $cache;
        $this->client = $client;
        $this->byodservice = $byodservice;
		$this->ir = $ir;
    }

    /**
     * @Route("/byod/forecasts", name="byod_forecasts")
     */
    public function forecastsIndex(Request $request, PaginatorInterface $paginator): Response
    {
        $cookies = $request->cookies;
        $academicYear = null;
        $iid = null;
        $institution = null;

		$institution = $this->byodservice->getForecastsDetails($cookies);

        if (empty($institution)) {
            $this->addFlash("info", "select an Institution first");
            return $this->redirectToRoute('home');
        }

        if ($cookies->has('academic_year')) {
            $academicYear = substr($cookies->get('academic_year'), -4);
        }

        if ($institution) {
            $cachKey = 'FORECASTS' . $institution->getId() . $academicYear;
            // $allforecasts = $this->api->getForecastsDetails($institution->getSynergyId(), $academicYear - 1);
            $allforecasts = $this->cache->get($cachKey, function (ItemInterface $item) use ($institution, $academicYear) {
                $item->expiresAfter(3600);
                return $this->api->getForecastsDetails($institution->getSynergyId(), $academicYear - 1);
            });
            // $forecasts = $paginator->paginate(
            //     $allforecasts,
            //     $request->query->getInt('page', 1),
            //     10
            // );

			$i = 0;
			// mark a forecast if there is record in shop_block table with the same forecast id
			foreach ($allforecasts as $forecast) {
				$block = $this -> api -> getBlock($forecast['id']);
				if (!empty($block)) {
					array_push($allforecasts[$i], 1);
				} else {
					array_push($allforecasts[$i], 0);
				}
				$i++;
			}

            return $this->render('byod/forecasts.html.twig', array(
                "forecasts" => $allforecasts
            ));
        }
    }


    /**
     * @Route("/byod/imageintakes", name="byod_imageintakes")
     */
    public function imageIntakesIndex(Request $request, PaginatorInterface $paginator): Response
    {
        $cookies = $request->cookies;
        $iid = $cookies->get('institution_id');
        $institution = null;
        $academicYear = null;

		$institution = $this->byodservice->getForecastsDetails($cookies);

		if (empty($institution)) {
            $this->addFlash("info", "select an Institution first");
            return $this->redirectToRoute('home');
        }

        if ($cookies->has('academic_year')) {
            $academicYear = substr($cookies->get('academic_year'), -4);
        }

        if ($institution) {
            $cachKey = 'INTAKES' . $institution->getId() . $academicYear;
            $allintakes = $this->cache->get($cachKey, function (ItemInterface $item) use ($institution, $academicYear) {
                $item->expiresAfter(3600);
                return $this->api->getImageIntakesForSchool($institution->getSynergyId(), $academicYear);
            });

            $intakes = $paginator->paginate(
                $allintakes,
                $request->query->getInt('page', 1),
                12
            );
            return $this->render('byod/intakes.html.twig', array(
                "intakes" => $intakes
            ));
        } else {
            return $this->redirectToRoute('accessdenied');
        }
    }

    /**
     * @Route("/byod/deliveries", name="byod_deliveries")
     */
    public function deliveriesIndex(Request $request, PaginatorInterface $paginator): Response
    {
        $cookies = $request->cookies;
        $iid = $cookies->get('institution_id');
        $institution = null;
        $academicYear = null;

		$institution = $this->byodservice->getForecastsDetails($cookies);

		if (empty($institution)) {
            $this->addFlash("info", "select an Institution first");
            return $this->redirectToRoute('home');
        }

        if ($cookies->has('academic_year')) {
            $academicYear = substr($cookies->get('academic_year'), -4);
        }

        if ($institution) {
            $cachKey = 'DELIVERIES' . $institution->getId() . $academicYear;
            $alldeliveries = $this->cache->get($cachKey, function (ItemInterface $item) use ($institution, $academicYear) {
                $temp = array();
                $item->expiresAfter(3600);
                //$guids = $this->api->getDeliveryGuidForSchool($institution->getSynergyId());
                $deliveries = $this->api->getDeliveriesForSchool($institution->getSynergyId(), $academicYear - 1);
                if (!empty($deliveries)) {
                    foreach ($deliveries as $delivery) {
                        $temp[] = $delivery;
                    }
                }
                return array(
                    "deliveries" => $temp
                );
            });

            return $this->render('byod/deliveries.html.twig', array(
                "deliveries" => $alldeliveries['deliveries'],
            ));
        } else {
            return $this->redirectToRoute('accessdenied');
        }
    }

    /**
     * @Route("/byod/autoappupdater", name="byod_autoappupdater")
     */
    public function softwareRequestsIndex(Request $request): Response
    {
        $cachKey = 'AUTOAPPUPDATER';
        $apps = $this->cache->get($cachKey, function (ItemInterface $item) {
            $item->expiresAfter(3600);
            $productionCounter = $this->api->getSoftwareRequests("production");
            $requestedCounter = $this->api->getSoftwareRequests("requested");
            $availableCounter = $this->api->getSoftwareRequests("available");
            $dataDetails = $this->api->getSoftwareRequests("data");
            return array(
                'production' => $productionCounter[0],
                'requests' => $requestedCounter[0],
                'available' => $availableCounter[0],
                'data' => $dataDetails
            );
        });

        return $this->render('byod/softwarerequests.html.twig', array(
            'production' => $apps['production'],
            'requests' => $apps['requests'],
            'available' => $apps['available'],
            'data' => $apps['data']
        ));
    }

    public function softwareRequestsAction(): Response
    {
        $sp2Url = $this->getParameter('app.sp2.url');
        $response = $this->client->request(
            'GET',
            $sp2Url . '/autoAppUpdaterSoftwareAdd.php'
        );
        $content = $response->getContent();
        return new Response($content);
    }

    /**
     * @Route("/byod/hires", name="byod_hires")
     */
    public function getAllHiresIndex(Request $request): Response
    {
        $cookies = $request->cookies;
        $iid = $cookies->get('institution_id');
        $institution = null;

		$institution = $this->byodservice->getForecastsDetails($cookies);

		if (empty($institution)) {
            $this->addFlash("info", "select an Institution first");
            return $this->redirectToRoute('home');
        }

        if (!$institution) {
            return $this->redirectToRoute('accessdenied');
        }

        $cachKey = 'HIRES' . $institution->getId();

        $allHires = $this->cache->get($cachKey, function (ItemInterface $item) use ($institution) {
            $item->expiresAfter(3600);
            return $this->api->getAllLeermiddel($institution->getSynergyId());
        });
        // $allHires =  $this->api->getAllLeermiddel($institution->getSynergyId());

        return $this->render('byod/hires.html.twig', array(
            "hires" => $allHires
        ));
    }

    /**
     * @Route("/byod/powerbi", name="byod_powerbi")
     */
    public function getLeermiddelPowerBILink(Request $request): Response
    {
        $cookies = $request->cookies;
        $iid = $cookies->get('institution_id');
        $institution = null;
        $temp = array();
        $reports = array();

		$institution = $this->byodservice->getForecastsDetails($cookies);

        if ($institution) {
            $data = $this->api->getLeermiddelPowerBI($institution->getSynergyId());

            foreach ($data as $dat) {
                foreach ($dat as $t) {
                    array_push($temp, $t);
                }
            }

            foreach ($temp as $report) {
                if ($report['Credit'] < $report['Debit']) {
                    array_push($reports, $report);
                }
            }
            return $this->render('byod/hiresreports.html.twig', array(
                "reports" => $reports
            ));
        } else {
            $this->addFlash("info", "select an Institution first");
            return $this->redirectToRoute('home');
        }
    }

	/**
	 * @Route("/byod/webshopordersM4S", name="webshopordersM4S")
	 */
	public function webshopordersM4S(Request $request): Response
	{
		$cookies = $request->cookies;
		$iid = null;
		if($cookies->has('institution_id')) {
			$iid = $cookies->get('institution_id');
		} else {
			$this->addFlash("info", "select an Institution first");
			return $this->redirectToRoute('home');
		}

		$institution = null;
		$temp = array();
		$webshopOrdersM4S = array();
		$rentOrders = array();
		$allData = array();
		$skuDevices = array();

		$institution = $this->ir->find($iid);
		if ($institution) {

			//$data = $this->api->getWebshopOrdersM4S($institution->getSynergyId());

            $cacheKey = "SHOPORDERS" . $institution->getSynergyId();
            $data = $this->cache->get($cacheKey, function(ItemInterface $item) use ($institution){
                $item->expiresAfter(3600);
                return array("refreshed" =>date("h:i:sa"),"data" => $this->api->getWebshopOrdersM4S($institution->getSynergyId()));
            });

			$index = 0;
			$totalLeermiddel = 0;
			$totalShop = 0;
			$totalPaidShop = 0;
			$awaitShop = 0;
			$totalPaidLeermiddel = 0;
			$awaitLeermiddel = 0;
			if(isset($data['data']['shop'])) {
				foreach ($data['data']['shop'] as $shopOrder) {
					$webshopOrdersM4S[] = $shopOrder;
					$allData[$index]['type'] = "Webshop";
					$allData[$index]['status'] = $shopOrder['status'];
					$allData[$index]['paymentID'] = $shopOrder['payment_id'];
					$allData[$index]['studentID'] = $shopOrder['student_idBuy'];
					$allData[$index]['studentFirstName'] = $shopOrder['student_firstnameBuy'];
					$allData[$index]['studentLastName'] = $shopOrder['student_lastnameBuy'];
					$allData[$index]['email'] = $shopOrder['emailBuy'];
					$allData[$index]['phone'] = $shopOrder['phoneBuy'];
					$allData[$index]['deviceID'] = $shopOrder['device_idBuy'];
					$allData[$index]['total'] = $shopOrder['totalBuy'];
					$allData[$index]['contract_signed'] = NULL;
					$allData[$index]['contractPDF'] = NULL;
					$allData[$index]['waarborg'] = NULL;
					$allData[$index]['totalPrice'] = NULL;
					$allData[$index]['value'] =  $shopOrder['value'];
					$allData[$index]['deviceID'] = $shopOrder['devices_id'];
					$allData[$index]['device'] = NULL;
					$allData[$index]['heddeviceID'] = $shopOrder['heddevices_id'];
					$allData[$index]['deviceManufacturer'] = $shopOrder['devices_manufacturer'];
					$allData[$index]['deviceModel'] = $shopOrder['devices_model'];
					$allData[$index]['heddeviceManufacturer'] = $shopOrder['heddevices_manufacturer'];
					$allData[$index]['heddeviceModel'] = $shopOrder['heddevices_model'];
					$allData[$index]['VoorschotOntvangen'] = NULL;
					$allData[$index]['DatumVoorschotOntvangen'] = NULL;
					$allData[$index]['MethodeVoorschotBetaald'] = NULL;
					$allData[$index]['UniqueIdentifier'] = NULL;
					$allData[$index]['field_title'] = $shopOrder['field_title'];
					if (!empty($shopOrder['value'])) {
						$allData[$index]['value'] = $shopOrder['value'];
					} else {
						$allData[$index]['value'] = "/";
					}

					if ($shopOrder['status'] === 'paid') {
						++$totalPaidShop;
					}

					if ($shopOrder['status'] === 'open') {
						++$awaitShop;
					}

					if ($shopOrder['status'] === 'open' || $shopOrder['status'] === 'paid')  {
						++$totalShop;
					}

					++$index;
				}
			}

			if(isset($data['data']['leermiddel'])) {
				foreach ($data['data']['leermiddel'] as $leermiddelOrder) {
					$rentOrders[] = $leermiddelOrder;
					$allData[$index]['type'] = "Leermiddel";
					$allData[$index]['status'] = NULL;
					$allData[$index]['paymentID'] = NULL;
					$allData[$index]['studentID'] = NULL;
					$allData[$index]['studentFirstName'] = $leermiddelOrder['student_firstname'];
					$allData[$index]['studentLastName'] = $leermiddelOrder['student_lastname'];
					$allData[$index]['email'] = $leermiddelOrder['email'];
					$allData[$index]['phone'] = $leermiddelOrder['phone'];
					$allData[$index]['deviceID'] = NULL;
					$allData[$index]['total'] = NULL;
					$allData[$index]['contract_signed'] = $leermiddelOrder['contract_signed'];
					$allData[$index]['contractPDF'] = $leermiddelOrder['contractPDF'];
					$allData[$index]['waarborg'] = $leermiddelOrder['waarborg'];
					$allData[$index]['totalPrice'] = $leermiddelOrder['totalPrice'];
					$allData[$index]['value'] = NULL;
					$allData[$index]['deviceID'] = $leermiddelOrder['device_id'];
					$allData[$index]['device'] = $leermiddelOrder['device'];
					$allData[$index]['heddeviceID'] = NULL;
					$allData[$index]['deviceManufacturer'] = NULL;
					$allData[$index]['deviceModel'] = NULL;
					$allData[$index]['heddeviceManufacturer'] = NULL;
					$allData[$index]['heddeviceModel'] = NULL;
					$allData[$index]['VoorschotOntvangen'] = $leermiddelOrder['VoorschotOntvangen'];
					$allData[$index]['DatumVoorschotOntvangen'] = $leermiddelOrder['DatumVoorschotOntvangen'];
					$allData[$index]['MethodeVoorschotBetaald'] = $leermiddelOrder['MethodeVoorschotBetaald'];
					$allData[$index]['UniqueIdentifier'] = $leermiddelOrder['UniqueIdentifier'];
					$allData[$index]['field_title'] = NULL;
					$allData[$index]['value'] = NULL;

					if ($leermiddelOrder['VoorschotOntvangen'] === 1) {
						++$totalPaidLeermiddel;
					}

					if ($leermiddelOrder['VoorschotOntvangen'] === 0) {
						++$awaitLeermiddel;
					}

					if ($leermiddelOrder['VoorschotOntvangen'] === 0 || $leermiddelOrder['VoorschotOntvangen'] === 1)  {
						++$totalLeermiddel;
					}

					++$index;
				}
			}


			return $this->render('byod/webshopordersM4S.html.twig', array(
				"webshopOrdersM4S" => $webshopOrdersM4S,
				"webshopRentOrders" => $rentOrders,
				"webshopAndRentOrders" => $allData,
				"totalPaidShop" => $totalPaidShop,
				"awaitShop" => $awaitShop,
				"totalPaidLeermiddel" => $totalPaidLeermiddel,
				"awaitLeermiddel" => $awaitLeermiddel,
				"totalShop" => $totalShop,
				"totalLeermiddel" => $totalLeermiddel,
				"devicesSku" => $data['data']['deviceSku'],
                "refreshed" => $data['refreshed']
			));
		} else {
			$this->addFlash("info", "select an Institution first");
			return $this->redirectToRoute('home');
		}
	}
}
