<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\AddUserType;
use App\Form\EditUserForSchoolAdminType;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/admin")
 */
class UserController extends AbstractController
{
    private $userRepo;
    private $passwordEncoder;
    private $em;

    public function __construct(UserRepository $userRepo, UserPasswordHasherInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $this->userRepo = $userRepo;
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $em;
    }
    /**
     * @Route("/user", name="admin_user")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $allUsers = null;
        $searchField = $request->get('filterValue');
        if ($searchField == null) {
            $allUsers = $this->userRepo->findAll();
        } else {
            $allUsers = $this->userRepo->findUsersByName($searchField);
        }

        $users = $paginator->paginate(
            $allUsers,
            $request->query->getInt('page', 1),
            25
        );

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     *  @Route("/user/edit/{id}", name="admin_user_edit") 
     */
    public function editUserAction(Request $request): Response
    {
        $id = $request->get('id');
        $user = $this->userRepo->find($id);

        if (!$user) {
            throw new \Exception("User with ID " . $id . " was not found");
        }
        $form = null;
        if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            $form = $this->createForm(EditUserType::class, $user);
        } elseif (in_array('ROLE_SCHOOLADMIN', $this->getUser()->getRoles())) {
            $form = $this->createForm(EditUserForSchoolAdminType::class, $user);
        } else {
            return $this->redirectToRoute('accessdenied');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash("success", "User {$user->getName()} werd geüpdate.");
            return $this->redirectToRoute('admin_user_edit', [
                'id' => $user->getId()
            ]);
        }

        return $this->render('admin/user/edit.html.twig', [
            'edit_user' => $form->createView(),
            'institutions' => $user->getInstitutions()
        ]);
    }

    /**
     *  @Route("/user/add", name="admin_user_add") 
     */
    public function addNewUser(Request $request): Response
    {
        $form = $this->createForm(AddUserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->userRepo->findUserByEmail($form->get('email')->getData());
            if ($user) {
                $this->addFlash("warning", "This user already exists");
                return $this->redirectToRoute("admin_user_add");
            }
            $user = new User();
            $user->setEmail($form->get('email')->getData());
            $user->setName($form->get('name')->getData());
            $user->setSynergyId(0);
            $user->setRoles($form->get('roles')->getData());
            $user->setPassword($this->passwordEncoder->hashPassword($user, bin2hex(random_bytes(16))));
            $user->setLocale($form->get('locale')->getData());
            $user->setShowRelease(true);

            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute("admin_user");
        }

        return $this->render('admin/user/add.html.twig', array(
            "add_user" => $form->createView()
        ));
    }

    /**
     * @Route("/user/remove", name="admin_user_remove")
     */
    public function removeUser(Request $request): Response
    {
        $uid = $request->get('uid');

        /** @var User $user */
        $user = $this->userRepo->find($uid);

        if (!$user) {
            throw new \Exception("User not found");
        }

        foreach ($user->getInstitutions() as $institution) {
            $institution->removeUser($user);
            $this->em->persist($institution);
            $this->em->flush();
        }

        $this->em->remove($user);
        $this->em->flush();

        return $this->redirectToRoute('admin_user');
    }
}
