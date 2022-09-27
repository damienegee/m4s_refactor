<?php

namespace App\Controller\Admin;

use App\Entity\Institution;
use App\Form\FreeFieldsType;
use App\Form\SchoolSettingsType;
use App\Repository\InstitutionRepository;
use App\Repository\UserRepository;
use App\Service\SP2ApiService;
use Cassandra\Set;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class SchoolSettingsController extends AbstractController
{
	private $api;
	private $institutions;

	public function __construct(SP2ApiService $api, InstitutionRepository $institutions)
	{
		$this->api = $api;
		$this->institutions = $institutions;
	}

	/**
	 * @Route("/school_settings", name="admin_school_settings")
	 */
	public function index(Request $request): Response
	{
		$cookies = $request->cookies;
		if ($cookies->has('institution_id') && $cookies->get('institution_id') !== '') {
			$institution = $this->institutions->find($cookies->get('institution_id'));
			if (!$institution) {
				throw new \Exception("institution not found");
			}
			$schoolId = $this->api->getSchoolForSynergy($institution->getSynergyId())[0];
			$settings = $this->api->getSchoolSettings($schoolId);

			$form = $this->createForm(SchoolSettingsType::class, ['use_school_facturation_default' => $settings['fs_use_school_billing'] > 0]);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				$useSchoolFacturationDefault = empty($form->get('use_school_facturation_default')->getData()) ? 0 : 1;
				$this->api->updateSchoolSettings($schoolId, $useSchoolFacturationDefault);
				return $this->redirectToRoute("admin");
			}

			return $this->render('admin/school_settings/index.html.twig', array(
				'form' => $form->createView()
			));
		} else {
			$this->addFlash("info", "Please select an Institution first");
			return $this->redirectToRoute('home');
		}
	}
}
