<?php

namespace App\Controller;

use App\Entity\Scripting;
use App\Form\ScriptingType;
use App\Form\SearchBoxType;
use App\Repository\ScriptingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScriptingController extends AbstractController
{
    private $sr;
    private $em;

    public function __construct(ScriptingRepository $sr, EntityManagerInterface $em)
    {
        $this->sr = $sr;
        $this->em = $em;
    }
    /**
     * @Route("/scripting", name="scripting")
     */
    public function index(Request $request): Response
    {
        $searchCriteria = $request->get('criteria');
        $scripts = array();

        if (is_null($searchCriteria)) {
            $scripts = $this->sr->findAll();
        } else {
            $scripts = $this->sr->findByName($searchCriteria);
        }

        $searchForm = $this->createForm(SearchBoxType::class);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted()) {
            // dd($searchForm->get('searchcriteria')->getData());
            $searchCriteria = $searchForm->get('searchcriteria')->getData();
            return $this->redirectToRoute('scripting', [
                "criteria" => $searchCriteria
            ]);
        }

        return $this->render('scripting/index.html.twig', [
            "scripts" => $scripts,
            "searchform" => $searchForm->createView()
        ]);
    }

    /**
     * @Route("/admin/scripting/add", name="addscripting")
     */
    public function addScripting(Request $request): Response
    {
        $scripts = $this->sr->findAll();

        $scriptDir = $this->getParameter('scripting_directory');
        $form = $this->createForm(ScriptingType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $script */
            $script = $form->get('path')->getData();
            $file = $script->getClientOriginalName();
            $script->move($scriptDir, $file);

            $entity = new Scripting();
            $entity->setName($form->get('name')->getData());
            $entity->setDescription($form->get('description')->getData());
            $entity->setCode($form->get('code')->getData());
            $entity->setPath($file);
            $this->em->persist($entity);
            $this->em->flush();
            return $this->redirectToRoute('addscripting');
        }
        return $this->render('scripting/form.html.twig', [
            'form' => $form->createView(),
            'scripts' => $scripts
        ]);
    }

    /**
     * @Route("/admin/scripting/remove/{id}", name="removescripting")
     */
    public function removeScript(Request $request): Response
    {
        $sid = $request->get('id');
        $script = $this->sr->find($sid);
        $this->em->remove($script);
        $this->em->flush();
        return $this->redirectToRoute('addscripting');
    }
}
