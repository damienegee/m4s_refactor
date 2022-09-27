<?php

namespace App\Controller;

use App\Entity\ExtraDevice;
use App\Entity\Images;
use App\Entity\Loan;
use App\Entity\ReturnedLoan;
use App\Form\ReturnLoanType;
use App\Repository\LoanRepository;
use App\Service\SP2ApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReturnedLoanController extends AbstractController
{
    private $api;
    private $lr;
    private $em;

    public function __construct(SP2ApiService $api, LoanRepository $lr, EntityManagerInterface $em)
    {
        $this->api = $api;
        $this->lr = $lr;
        $this->em = $em;
    }
    /**
     * @Route("/returned/loan", name="returned_loan")
     */
    public function index(): Response
    {
        return $this->render('returned_loan/index.html.twig', [
            'controller_name' => 'ReturnedLoanController',
        ]);
    }

    /**
     * @Route("/returned/loan/new", name="returned_loan_new")
     */
    public function createNewReturnedLoanAction(Request $request): Response
    {
        $loanId = $request->get('loanId');

        /** @var Loan $loan */
        $loan = $this->lr->find($loanId);
        if (!$loan) {
            throw new \Exception("Loan not found");
        }

        $form = $this->createForm(ReturnLoanType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ReturnedLoan $returnedLoan */
            $returnedLoan = $form->getData();

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
                $returnedLoan->addImage($img);
            }

            $loan->setEnddate($returnedLoan->getReturneddate());

            $this->em->persist($loan);
            $this->em->flush();

            $returnedLoan->setLoan($loan);
            $this->em->persist($returnedLoan);
            $this->em->flush();

            // remove client from device on SP2
            //$this->api->removeClientFromDevice($loan->getUser(), $loan->getDeviceSerial(), $loan->getM4sSchoolId());
            if ($loan->getIsExtra()) {
                // /** @var ExtraDevice $exd */
                // $exd = $this->getDoctrine()->getManager()->getRepository(ExtraDevice::class)->find($loan->getDeviceId());
                // if (!$exd) {
                //     throw new \Exception("this device could not be found");
                // }
                // $exd->setCustomer_id(NULL);
                // $em->persist($exd);
                // $em->flush();
                $this->api->updateCustomerOnExtraDevice($loan->getDeviceId());
            } else {
                $this->api->updateCustomerOnDevice($loan->getDeviceId());
            }


            return $this->redirectToRoute('loan', array(
                'location_id' => $loan->getSchoolloationId()
            ));
        }

        return $this->render("returned_loan/form.html.twig", [
            "form" => $form->createView(),
        ]);
    }
}
