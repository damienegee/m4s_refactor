<?php

namespace App\Service;

use App\Entity\Institution;
use App\Repository\InstitutionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BYODService extends AbstractController
{

    protected $container;
    private $api;
	private $ir;

    public function __construct(ContainerInterface $container, SP2ApiService $api, InstitutionRepository $ir)
    {
        $this->container = $container;
        $this->api = $api;
		$this->ir = $ir;
    }

    public function getForecastsDetails($cookies)
    {
		$iid = null;
		$institution = null;

		if ($cookies->has('institution_id') && $cookies->get('institution_id') !== '') {
			$iid = $cookies->get('institution_id');
			/** @var Institution $institution */
			$institution = $this->getInstitution($iid);
		} elseif ($cookies->has('location_id') && $cookies->get('location_id') !== '') {
			$sp2Schoollocation = $this->api->getSchoollocation($cookies->get('location_id'));
			$institution = $this->getInstitutionBySynergy($sp2Schoollocation[0]['synergyid']);
		}

        return  $institution;
    }

	// private function in the past
	public function getInstitution($iid): Institution
	{
		$ret = null;

		/** @var Institution $institution */
		$institution = $this->ir->find($iid);

		if ($this->checkInstitution($institution)) {
			$ret = $institution;
		}

		return $ret;
	}

	// private function in the past
	public function getInstitutionBySynergy($synergy): Institution
	{
		$ret = null;

		/** @var Institution $institution */
		$institution = $this->ir->findInstitutionBySynergy($synergy);

		if ($this->checkInstitution($institution)) {
			$ret = $institution;
		}

		return $ret;
	}

	// private function in the past
	public function checkInstitution(Institution $institution)
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
