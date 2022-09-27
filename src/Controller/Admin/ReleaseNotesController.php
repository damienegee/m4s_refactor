<?php

namespace App\Controller\Admin;

use App\Entity\ReleaseNotes;
use App\Entity\User;
use App\Form\ReleaseNotesType;
use App\Repository\ReleaseNotesRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReleaseNotesController extends AbstractController
{
    private $rnr;
    private $em;
    private $ur;

    public function __construct(ReleaseNotesRepository $rnr, EntityManagerInterface $em, UserRepository $ur)
    {
        $this->rnr = $rnr;
        $this->em = $em;
        $this->ur = $ur;
    }
    /**
     * @Route("/admin/release/notes", name="admin_release_notes")
     */
    public function index(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $releasNotes = array();
        $releases = $this->rnr->findAll();
        /** @var TranslationRepository $repository */
        $repository = $this->em->getRepository('Gedmo\Translatable\Entity\Translation');
        foreach ($releases as $rn) {
            $rn->setTranslatableLocale($user->getLocale());
            $this->em->refresh($rn);
            array_push($releasNotes, $rn);
        }

        $form = $this->createForm(ReleaseNotesType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rn = new ReleaseNotes;
            $rn->setCreated(new DateTime());
            $rn->setVersion($form->get('version')->getData());

            foreach ($this->locallist() as $lang) {
                $repository->translate($rn, 'title', $lang, $form->get('title_' . $lang)->getData())
                    ->translate($rn, 'description', $lang, $form->get('description_' . $lang)->getData());
            }

            $this->em->persist($rn);
            $this->em->flush();

            // set the showmodal for all user to true when new releasenotes is added
            $users = $this->ur->findAll();
            foreach ($users as $user) {
                // if (!in_array("ROLE_ADMIN", $user->getRoles())) {
                $user->setShowRelease(true);
                $this->em->persist($user);
                $this->em->flush();
                // }
            }

            return $this->redirectToRoute('admin_release_notes');
        }

        return $this->render('admin/release_notes/index.html.twig', array(
            "releasenotes" => $releasNotes,
            "form" => $form->createView()
        ));
    }

    private function locallist()
    {
        $ret = array();
        $array = (explode(',', $this->getParameter('app.enabledlang')));
        foreach ($array as $lang) {
            $ret[$lang] = $lang;
        }
        return $ret;
    }
}
