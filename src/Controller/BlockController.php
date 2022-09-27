<?php
namespace App\Controller;

use App\Entity\BlockIntroContent;
use App\Form\BlockType;
use App\Service\BYODService;
use App\Service\SP2ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class BlockController extends AbstractController
{
	private $api;
	private $slugger;
	private $byodservice;
	private $cache;

	public function __construct(SP2ApiService $api, SluggerInterface $slugger, BYODService $byodservice, CacheInterface $cache)
	{
		$this->api = $api;
		$this->slugger = $slugger;
		$this->byodservice = $byodservice;
		$this->cache = $cache;
	}

	/**
	 * @Route("/byod/forecasts/block/{id}", name="block")
	 */
	public function index(Request $request): Response
	{
		$cookies = $request->cookies;
		$webshop_id = $request->get('id');
		$encodedFile = $file = $filePath = $extTranslationsData = $schoolLogo = $institution = $academicYear = $allForecasts = null;

		$languages = [
			'fr',
			'es',
			'en',
			'de'
		];

		$content = BlockIntroContent::getContent();
		$blockData = $this->api->getBlock($webshop_id);
		$extTranslationsData = !empty($blockData[0]['id']) ? $this->api->getExtTranslations($blockData[0]['id']) : '';  // otherwise, error because of empty index

		// to get school
		$institution = $this->byodservice->getForecastsDetails($cookies);
		if ($cookies->has('academic_year')) {
			$academicYear = substr($cookies->get('academic_year'), -4);
		}

		// to get all forecasts
		if (!empty($institution)) {
			$cachKey = 'FORECASTS' . $institution->getId() . $academicYear;
			$allForecasts = $this->cache->get($cachKey, function (ItemInterface $item) use ($institution, $academicYear) {
				$item->expiresAfter(3600);
				return $this->api->getForecastsDetails($institution->getSynergyId(), $academicYear - 1);
			});
		}

		$form = $this->createForm(BlockType::class, null, array('forecasts' => $allForecasts, 'webshop_id' => $webshop_id));
		$form->handleRequest($request);

		if ($form->isSubmitted()) {
			if ($form->isValid()) {
				$formData = $form->getData();
				$schoolLogo = $form->get('school_logo')->getData();

				// to properly save logo
				if (!empty($schoolLogo)) {
					$originalFilename = pathinfo($schoolLogo->getClientOriginalName(), PATHINFO_FILENAME);
					$ext = pathinfo($schoolLogo->getClientOriginalName(), PATHINFO_EXTENSION);
					$safeFilename = $this->slugger->slug($originalFilename);
					$newFilename = $safeFilename.'-'.uniqid().'.'.$ext;
					// Move the file to the directory where uploads are stored
					$schoolLogoNew = $schoolLogo->move($this->getParameter('uploads_directory'), $newFilename);
					unset($schoolLogo);
					$encodedFile = 'data:image/' . $ext . ';base64,' . base64_encode(file_get_contents($schoolLogoNew));
				} else {
					$encodedFile = $blockData[0]['school_logo'] ?? '';
				}

				// to properly fill in course_label
				if (!empty($formData['course_label'])) {
					$course_label = $formData['course_label'];
				}  else {
					$course_label = $blockData[0]['course_label'] ?? '';
				}

				// adding other information to this specific forecast
				if (empty($blockData) && empty($extTranslationsData)) { // check if this block and translations already exist
					$this->api->addBlock($webshop_id, $formData['title_nl'], $formData['description_nl'], $encodedFile, $course_label);
					$blockData = $this->api->getBlock($webshop_id); // get block data again since it was refilled
					foreach ($languages as $lang) {
						$this->api->addExtTranslations($lang, 'App\Entity\Block', 'title', $blockData[0]['id'], $formData['title_'.$lang]);
						$this->api->addExtTranslations($lang, 'App\Entity\Block', 'content', $blockData[0]['id'], $formData['description_'.$lang]);
					}
				} else {
					$this->api->updateBlock($webshop_id, $formData['title_nl'], $formData['description_nl'], $encodedFile, $course_label);
					foreach ($languages as $lang) {
						$this->api->updateExtTranslations($lang, 'title', $blockData[0]['id'], $formData['title_'.$lang]);
						$this->api->updateExtTranslations($lang, 'content', $blockData[0]['id'], $formData['description_'.$lang]);
					}
				}

				// adding/updating logo -> for all forecasts at once by default
				$blockData = $this->api->getBlock($webshop_id); // get block data again since it was refilled
				foreach ($allForecasts as $fc) {
					$sbData = $this->api->getBlock($fc['id']);
					if ($fc['id'] != $webshop_id) {

						if ($formData['checkExtraInfo'] === true && $formData[$fc['id']]) {
							$course_label = $blockData[0]['course_label'];
						} else {
							$course_label =  $sbData[0]['course_label'] ?? '';
						}

						if (empty($sbData)) {
							$this->api->addBlock($fc['id'], $formData['title_nl'], $formData['description_nl'], $encodedFile, $course_label);
							$sbData = $this->api->getBlock($fc['id']);
							foreach ($languages as $lang) {
								$this->api->addExtTranslations($lang, 'App\Entity\Block', 'title', $sbData[0]['id'], $formData['title_'.$lang]);
								$this->api->addExtTranslations($lang, 'App\Entity\Block', 'content', $sbData[0]['id'], $formData['description_'.$lang]);
							}
						} else {
							$this->api->updateBlock($fc['id'], $formData['title_nl'], $formData['description_nl'], $encodedFile, $course_label);
							foreach ($languages as $lang) {
								$this->api->updateExtTranslations($lang, 'title', $sbData[0]['id'], $formData['title_'.$lang]);
								$this->api->updateExtTranslations($lang, 'content', $sbData[0]['id'], $formData['description_'.$lang]);
							}
						}
					}
				}

				return $this->redirectToRoute('byod_forecasts');
			}
		} else {
			if ($blockData && $extTranslationsData) {
				$file = $blockData[0]['school_logo'];
				$form->get('course_label')->setData($blockData[0]['course_label']);
				$form->get('title_nl')->setData($blockData[0]['title']);
				$form->get('description_nl')->setData($blockData[0]['content']);
				foreach ($languages as $lang) {
						$form->get('title_' . $lang)->setData($extTranslationsData[$lang]['title']);
						$form->get('description_' . $lang)->setData($extTranslationsData[$lang]['content']);
				}
			} else {
				$file = $blockData[0]['school_logo'] ?? '';
				$form->get('course_label')->setData($blockData[0]['course_label'] ?? '');
				$form->get('title_nl')->setData($blockData[0]['title'] ?? $content['title_nl']);
				$form->get('description_nl')->setData($blockData[0]['content'] ?? $content['content_nl']);
				foreach ($languages as $lang) {
					$form->get('title_' . $lang)->setData($extTranslationsData[0]['content'] ?? $content['title_' . $lang]);
					$form->get('description_' . $lang)->setData($extTranslationsData[0]['content'] ?? $content['content_' . $lang]);
				}
			}
		}

		return $this->render('block/index.html.twig', array(
			"form" => $form->createView(),
			'id' => $webshop_id,
			'file' => $file,
			'forecasts' => $allForecasts
		));
	}

}