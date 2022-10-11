<?php

namespace App\Controller;

use App\Entity\Institution;
use App\Entity\User;
use App\Form\SearchBoxType;
use App\Form\SubmitType;
use App\Repository\InstitutionRepository;
use App\Repository\ReleaseNotesRepository;
use App\Service\SP2ApiService;

use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class HomeController extends AbstractController
{
    private $ir;
    private $api;
    private $cache;
    private $em;
    private $rnr;

    public function __construct(InstitutionRepository $ir, SP2ApiService $api, CacheInterface $cache, EntityManagerInterface $em, ReleaseNotesRepository $rnr)
    {
        $this->ir = $ir;
        $this->api = $api;
        $this->cache = $cache;
        $this->em = $em;
        $this->rnr = $rnr;
    }

	/**
	 * @Route("/{reactRouting}", name="app_default", defaults={"reactRouting": null})
	 */
	public function index(): Response
	{
		return $this->render('default/index.html.twig');
	}

//    /**
//     * @Route("/", name="root")
//     */
//    public function index(Request $request): Response
//    {
//        if ($this->getUser()) {
//            /** @var User $user */
//            $user = $this->getUser();
//            if (count($user->getInstitutions()) === 1) {
//                $cookie = new Cookie('institution_id', $user->getInstitutions()[0]->getId(), time() + 60);
//                $response = new RedirectResponse($this->generateUrl('home'));
//                $response->headers->setCookie($cookie);
//                return $response;
//            } else {
//                return $this->redirectToRoute('home');
//            }
//        } else {
//            return $this->render('login');
//        }
//    }

    /**
     * @Route("/releasenotes", name="releasenotes")
     */
    public function showReleaseNotesModal(Request $request): Response
    {
        if ($this->getUser()) {
            /** @var User $user */
            $user = $this->getUser();
            if (!is_null($user->getShowRelease()) && $user->getShowRelease()) {
                $releases = $this->rnr->findAllOrderByCreated();
                $releasNotes = array();

                foreach ($releases as $rn) {
                    $rn->setTranslatableLocale($user->getLocale());
                    $this->em->refresh($rn);
                    array_push($releasNotes, $rn);
                }

                $submitButton = $this->createForm(SubmitType::class);
                $submitButton->handleRequest($request);
                if ($submitButton->isSubmitted()) {
                    $user->setShowRelease(false);
                    $this->em->persist($user);
                    $this->em->flush();
                    return $this->redirectToRoute('root');
                }
                return $this->render('home/showreleasenotes.html.twig', array(
                    "submitButton" => $submitButton->createView(),
                    "releasenotes" => $releasNotes
                ));
            }
        } else {
            return $this->render('login');
        }
    }

    /**
     * @Route("/home", name="home")
     */
    public function home(Request $request, PaginatorInterface $paginator)
    {
        if ($this->getUser()) {
            /** @var User $user */
            $user = $this->getUser();

            if (in_array('ROLE_AGSO', $this->getUser()->getRoles())) {
                return $this->redirectToRoute('agso');
            } else {
                $cookies = $request->cookies;
                if ($cookies->has('institution_id') && $cookies->get('institution_id') != '') {
                    return $this->redirectToRoute('institutionDetail', array(
                        'id' => $cookies->get('institution_id')
                    ));
                } else if ($cookies->has('location_id') && $cookies->get('location_id') != "") {
                    return $this->redirectToRoute('institutionlocationForId', array(
                        'lid' => $cookies->get('location_id')
                    ));
                }
                $institutions = array();
                $searchField = $request->get('filterValue');
                if (in_array('ROLE_ADMIN', $user->getRoles())) {
                    if ($searchField == null) {
                        $institutions = $this->ir->findAll();
                    } else {
                        $institutions = $this->ir->findInstitutionByName($searchField);
                    }
                } else {
                    $institutions = array();
                    if ($searchField == null) {
                        $institutions = $user->getInstitutions();
                    } else {
                        foreach ($institutions as $inst) {
                            if (strpos($inst->getInstitutionName(), $searchField)) {
                                array_push($institution, $inst);
                            }
                        }
                    }
                }

                $allInstitutions = $paginator->paginate(
                    $institutions,
                    // define the page parameter
                    $request->query->getInt('page', 1),
                    12
                );

                return $this->render('home/index.html.twig', array(
                    'institutions' => $allInstitutions
                ));
            }
        } else {
            return $this->render('login');
        }
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboard(Request $request, PaginatorInterface $paginator): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $institutions = array();
        $searchField = $request->get('filterValue');
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            if ($searchField == null) {
                $institutions = $this->ir->findAll();
            } else {
                $institutions = $this->ir->findInstitutionByName($searchField);
            }
        } else {
            $institutions = $user->getInstitutions();
            if ($searchField == null) {
                $institutions = $user->getInstitutions();
            } else {
                foreach ($institutions as $inst) {
                    if (strpos($inst->getInstitutionName(), $searchField)) {
                        array_push($institution, $inst);
                    }
                }
            }
        }

        $allInstitutions = $paginator->paginate(
            $institutions,
            // define the page parameter
            $request->query->getInt('page', 1),
            12
        );
        return $this->render('home/index.html.twig', array(
            'institutions' => $allInstitutions
        ));
    }

    /**
     * @Route("/changeLocale", name="changeLocale")
     */
    public function changeLocale(Request $request)
    {
        $locale = $request->get('locale');
        /** @var User $user */
        $user = $this->getUser();
        $user->setLocale($locale);
        $this->em->persist($user);
        $this->em->flush();
        $request->getSession()->set('_locale', $user->getLocale());

        return $this->redirectToRoute('root');
    }

    /**
     * @Route("/searchdeviceoruser", name="searchDeviceOrUser")
     */
    public function searchDeviceOrUser(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $criteria = null;
        $searchForm = $this->createForm(SearchBoxType::class);
        $searchForm->handleRequest($request);
        $result = null;
        $schoolIds = null;
        if (!$user->hasRole('ROLE_ADMIN')) {
            foreach ($user->getInstitutions() as $institution) {
                $schoolIds = $this->api->getSchoolForSynergy($institution->getSynergyId());
            }
        }

        if ($searchForm->isSubmitted()) {
            $criteria = $searchForm->get('searchcriteria')->getData();
            $result = $this->api->searchWithCriteria($criteria, $schoolIds);
            if (count($result) === 1) {
                if ($result[0]['colFour'] === 'device') {
                    return $this->redirectToRoute("invetoryDeviceDetails", array(
                        'id' => $result[0]['colOne']
                    ));
                } elseif ($result[0]['colFour'] === 'customer') {
                    return $this->redirectToRoute("customerdetails", array(
                        'cid' => $result[0]['colOne']
                    ));
                }
            } else {
                return $this->render('utilities/_searchresults.html.twig', array(
                    'results' => $result
                ));
            }

            $this->addFlash('warning', 'Nothing found');
            return $this->redirectToRoute('home');
        }

        return $this->render('utilities/_searchbox.html.twig', array(
            'searchform' => $searchForm->createView()
        ));
    }

    public function listInstitutionAction(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $res = preg_replace("/[^a-zA-Z]/", "", $user->getEmail());
        $cachKey = $res . 'institutions';
        $insts = $this->cache->get($cachKey, function (ItemInterface $item) {
            $item->expiresAfter(3600);
            $institutions = array();
            $newArray = array();
            /** @var User $user */
            $user = $this->getUser();
            if (in_array('ROLE_ADMIN', $user->getRoles())) {
                $institutions = $this->ir->findAll();
            } else {
                $institutions = $user->getInstitutions()->toArray();
            }
            usort($institutions, array("App\\Utils\\Utilities", 'sortInstitutionByName'));

            foreach ($institutions as $institution) {

                $locations = $this->api->getSchoollocationsForSchool($institution->getSynergyId());
                if (!empty($locations)) {
                    $newArray[$institution->getId()] = array(
                        'name' => $institution->getInstitutionName(),
                        'id' => $institution->getId(),
                        'synergyId' => $institution->getSynergyId(),
                        'locations' => array()
                    );
                    foreach ($locations as $location) {
                        array_push($newArray[$institution->getId()]['locations'], $location);
                    }
                }
            }
            return $newArray;
        });

        return $this->render('utilities/_institutionselector.html.twig', array(
            "institutions" => $insts
        ));
    }

    public function listReleaseNotesAction(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $releases = $this->rnr->findAllOrderByCreated();
        $releasNotes = array();

        foreach ($releases as $rn) {
            $rn->setTranslatableLocale($user->getLocale());
            $this->em->refresh($rn);
            array_push($releasNotes, $rn);
        }

        return $this->render('release_notes/release_notes.html.twig', array(
            'releasenotes' => $releasNotes
        ));
    }
}
