<?php

namespace App\Controller\Admin;

use App\Entity\Institution;
use App\Entity\User;
use App\Form\UserForSchoolType;
use App\Repository\InstitutionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class UserForSchoolController extends AbstractController
{

    private $passwordEncoder;
    private $ur;
    private $ir;
    private $em;

    public function __construct(UserPasswordHasherInterface $passwordEncoder, UserRepository $ur, InstitutionRepository $ir, EntityManagerInterface $em)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->ur = $ur;
        $this->ir = $ir;
        $this->em = $em;
    }
    /**
     * @Route("/user/for/school", name="admin_user_for_school")
     */
    public function index(): Response
    {
        return $this->render('admin/user_for_school/index.html.twig', [
            'controller_name' => 'UserForSchoolController',
        ]);
    }

    /**
     * @Route("/usersforschool", name="listUserForSchool")
     */
    public function listUsersForSchool(Request $request, PaginatorInterface $paginator): Response
    {
        $institutions = array();
        /** @var User $user */
        $user = $this->getUser();

        if (in_array('ROLE_SCHOOLADMIN', $user->getRoles())) {
            $institutions = $user->getInstitutions();
        } else if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $searchField = $request->get('filterValue');
            if ($searchField) {
                $institutions = $this->ir->findInstitutionByName($searchField);
            } else {
                $institutions = $this->ir->findAll();
            }
        }

        $allInstitutions = $paginator->paginate(
            $institutions,
            // define the page parameter
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/user_for_school/listusers.html.twig', array(
            "institutions" => $allInstitutions
        ));
    }

    /**
     * @Route("/usersforschool/add", name="addUserForSchool")
     */
    public function addUserForSchool(Request $request): Response
    {
        $iid = $request->get('iid');

        $institution = $this->ir->find($iid);

        if (!$institution) {
            throw new \Exception("institution not found with ID " . $iid);
        }

        $form = $this->createForm(UserForSchoolType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // check if the mail adres already exists
            $user = $this->ur->findUserByEmail($form->get('email')->getData());
            if (!$user) {
                $user = new User();
                $user->setEmail($form->get('email')->getData());
                $user->setName($form->get('name')->getData());
                $user->setSynergyId(0);
                $user->setRoles(['ROLE_USER']);
                $user->setPassword($this->passwordEncoder->hashPassword($user, bin2hex(random_bytes(16))));
                $user->setLocale('en');
                $user->setShowRelease(true);
            }

            $user->addInstitution($institution);

            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('listUserForSchool');
        }

        return $this->render('admin/user_for_school/addusertoschoolform.html.twig', array(
            "form" => $form->createView()
        ));
    }

    /**
     * @Route("/usersforschool/remove", name="removeUserForSchool")
     */
    public function removeUserForSchool(Request $request): Response
    {
        $sid = $request->get('sid');
        $uid = $request->get('uid');

        /** @var Institution $institution */
        $institution = $this->ir->find($sid);
        if (!$institution) {
            throw new \Exception("Institution could not be found");
        }

        /** @var User $user */
        $user = $this->ur->find($uid);
        if (!$user) {
            throw new \Exception("User could not be found");
        }

        $institution->removeUser($user);

        $this->em->persist($institution);
        $this->em->flush();

        return $this->redirectToRoute('listUserForSchool');
    }
}
