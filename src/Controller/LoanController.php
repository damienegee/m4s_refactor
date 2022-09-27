<?php

namespace App\Controller;

use App\Entity\ExtraDevice;
use App\Entity\Images;
use App\Entity\Institution;
use App\Entity\Loan;
use App\Entity\User;
use App\Form\LoanType;
use App\Repository\InstitutionRepository;
use App\Repository\LoanRepository;
use App\Service\SP2ApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoanController extends AbstractController
{

    private $api;
    private $lr;
    private $ir;
    private $em;

    public function __construct(SP2ApiService $api, LoanRepository $lr, InstitutionRepository $ir, EntityManagerInterface $em)
    {
        $this->api = $api;
        $this->lr = $lr;
        $this->ir = $ir;
        $this->em = $em;
    }

    /**
     * @Route("/loan", name="loan")
     */
    public function index(Request $request): Response
    {
        $locid = $request->get('location_id');

        $loans = $this->lr->findByLocationId($locid);

        return $this->render('loan/index.html.twig', [
            'loans' => $loans,
        ]);
    }

    /**
     * @Route("/loan/new", name="loan_new")
     */
    public function createNewLoan(Request $request): Response
    {
        $locationId = $request->get('locationid');
        $productnumber = $request->get('productnumber');
        $model = $request->get('model');
        $did = $request->get('did');
        $serialnumber = $request->get('serialnumber');

        $form = $this->createForm(LoanType::class, null, array(
            "location_id" => $locationId
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Loan $loan */
            $loan = $form->getData();

            if ($loan->getSignature() === NULL) {
                $this->addFlash("danger", "Een handtekening is verplicht");
                return $this->render("loan/form.html.twig", [
                    "form" => $form->createView(),
                ]);
            }

            $loan->setSchoollocationId($locationId);
            $loan->setDeviceId($did);

            // handle the images
            $images = $form->get('images')->getData();
            foreach ($images as $image) {
                // create a new file name
                $file = md5(uniqid()) . '.' . $image->guessExtension();
                // copy the files into the upload dir
                $image->move(
                    $this->getParameter('images_directory'),
                    $file
                );

                // create the image in DB
                $img = new Images();
                $img->setName($file);
                $loan->addImage($img);
            }

            $loan->setDeviceSerial($serialnumber);

            if ($request->get('forextra')) {
                //$exd = $this->getDoctrine()->getManager()->getRepository(ExtraDevice::class)->find($did);
                //$exd = $this->api->getE
                // if(!$exd) {
                //     throw new \Exception("this device could not be found");
                // }
                $this->api->updateCustomerOnExtraDevice($did, $loan->getUser());
                // $exd->setCustomer_id($loan->getUser());
                // $this->getDoctrine()->getManager()->persist($exd);
                // $this->getDoctrine()->getManager()->flush();
                $loan->setIsExtra(true);
            } else {
                $this->api->updateCustomerOnDevice($did, $loan->getUser());
                $loan->setIsExtra(false);
            }

            $this->em->persist($loan);
            $this->em->flush();

            return $this->redirectToRoute('loan', array(
                "location_id" => $locationId
            ));
        } else {
            if ($serialnumber) {
                $form->get('deviceSerial')->setData($serialnumber);
            }
        }

        return $this->render("loan/form.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
