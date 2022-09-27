<?php

namespace App\Controller\Admin;

use App\Form\InstitutionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin")
 */
class InstitutionController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @Route("/institution", name="admin_institution")
     */
    public function index(): Response
    {
        return $this->render('admin/institution/index.html.twig', [
            'controller_name' => 'InstitutionController',
        ]);
    }

    /**
     * @Route("/institution/add", name="admin_add_institution")
     */
    public function addInstitution(Request $request): Response
    {
        $form = $this->createForm(InstitutionType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Institution $institution */
            $institution = $form->getData();
            $this->em->persist($institution);
            $this->em->flush();

            return $this->redirectToRoute('listUserForSchool');
        }
        return $this->render('admin/institution/form.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
