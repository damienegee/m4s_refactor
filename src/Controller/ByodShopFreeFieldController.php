<?php

namespace App\Controller;

use App\Entity\Institution;
use App\Form\ByodShopFreeFieldType;
use App\Repository\InstitutionRepository;
use App\Service\SP2ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ByodShopFreeFieldController extends AbstractController
{

	private $api;
	private $ir;

	public function __construct(SP2ApiService $api, InstitutionRepository $ir)
	{
		$this->api = $api;
		$this->ir = $ir;
	}

	/**
	 * @Route("/byodshop/freefield", name="byod_shop_free_field")
	 */
	public function index(Request $request): Response
	{
		$fieldId = $request->get('fieldId');
		$cookies = $request->cookies;
		if ($cookies->has('institution_id') && $cookies->get('institution_id') !== '') {
			/** @var Institution $institution */
			$institution = $this->ir->find($cookies->get('institution_id'));

			$synergyId = $institution->getSynergyId();
			$schoolId = $this->api->getSchoolForSynergy($synergyId)[0];

			if (!$institution) {
				throw new \Exception("institution not found");
			}

//			$sp2School = $this->api->getSchoolForSynergy($institution->getSynergyId());
			//$schoolId[0]

			$form = $this->createForm(ByodShopFreeFieldType::class);

			if ($fieldId) {
				$field = $this->api->freeFieldForByodShop('', 'find', $fieldId);

				$form->get('fieldTitle')->setData($field[0]['field_title']);
				$form->get('fieldType')->setData($field[0]['field_type']);
				$form->get('active')->setData(($field[0]['active'] === 1) ? true : false);
				$form->get('required')->setData(($field[0]['required'] === 1) ? true : false);
			}
			$form->handleRequest($request);

			$fieldTitle = $form->get('fieldTitle')->getData();
			$fieldType = $form->get('fieldType')->getData();
			$active = $form->get('active')->getData();
			$required = $form->get('required')->getData();



			if ($form->isSubmitted() && $form->isValid()) {

				if ($fieldId) {
					$this->api->freeFieldForByodShop($schoolId, 'update', $fieldId, $fieldTitle, $fieldType, $active, $required);
				} else {
					$this->api->freeFieldForByodShop($schoolId, 'create', '', $fieldTitle, $fieldType, $active, $required);
				}
			}

			$freeFields = $this->api->freeFieldForByodShop($schoolId, 'get');

			return $this->render('byod_shop_free_field/index.html.twig', [
				'form' => $form->createView(),
				'freeFields' => empty($freeFields) ? array() : $freeFields,
				'school_id' => $institution->getId(),
			]);
		} else {
			$this->addFlash("info", "select an Institution first");
			return $this->redirectToRoute('home');
		}
	}

	/**
	 * @Route("/byodshop/freefield/deletefield", name="byod_free_field_remove")
	 */
	public function deleteField(Request $request): Response
	{
		$fieldId = $request->get('fieldId');
		$this->api->freeFieldForByodShop('', 'delete', $fieldId);
		return $this->redirectToRoute('byod_shop_free_field');
	}


}
