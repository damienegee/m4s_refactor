<?php

namespace App\Service;


use App\Entity\Institution;

use App\Entity\User;

use App\Repository\InstitutionRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MigrateM4SUser
{

    private $entityManager;
    private $managerRegistry;
    private $passwordEncoder;
    private $ir;
    private $ur;
    private $logger;
    private $api;
    private $notfound = array();

    private $usersQuery = "
        SELECT DISTINCT
            user.firstname as firstname,
            user.lastname as lastname,
            user.mail as email,
            school.synergyid as synergyid,
            school.name as schoolname
        FROM user
        INNER JOIN user_school
        ON user_school.user_id = user.id
        INNER JOIN school
        ON school.id = user_school.school_id
        WHERE school.deleted != 1
        AND user.deleted != 1
    ";

    public function __construct(
        ManagerRegistry $managerRegistry,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordEncoder,
        InstitutionRepository $ir,
        UserRepository $ur,
        LoggerInterface $logger,
        SP2ApiService $api
    ) {
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->ir = $ir;
        // $this->ilr = $ilr;
        $this->ur = $ur;
        $this->logger = $logger;
        $this->api = $api;
    }

    public function migrateM4SUser()
    {
        /** @var ConnectionRegistry $conn */
        $conn = $this->managerRegistry->getManager('byod_m4s');
        $statement = $conn->getConnection()->prepare($this->usersQuery);

        $resultSet = $statement->executeQuery();

        $results = $resultSet->fetchAllAssociative();

        foreach ($results as $item) {
            // All Signpost users get Admin Role
            if ($item['schoolname'] === 'Signpost') {
                $user = $this->ur->findUserByEmail($item['email']);
                if (!$user) {
                    $user = new User();
                    $user->setName($item['firstname'] . " " . $item['lastname']);
                    $user->setEmail($item['email']);
                    $user->setRoles(['ROLE_ADMIN']);
                    $user->setPassword($this->passwordEncoder->hashPassword($user, bin2hex(random_bytes(16))));
                    $user->setSynergyId(0);
                    $user->setLocale('nl');
                }
                $this->entityManager->persist($user);
                $this->entityManager->flush();
            } else {
                // before doing anything, check first if the synergyId exists in SP2
                $sp2Institution = $this->api->getSchoolForSynergy($item['synergyid']);
                if (empty($sp2Institution)) {
                    // $this->logger->info("School not found inside SP2 with synergy: " . $item['synergyid'] );
                    $notfound[$item['synergyid']][] = "School not found inside SP2";
                } else {
                    $user = $this->ur->findUserByEmail($item['email']);
                    if (!$user) {
                        $user = new User();
                        $user->setName($item['firstname'] . " " . $item['lastname']);
                        $user->setEmail($item['email']);
                        $user->setRoles(['ROLE_USER']);
                        $user->setPassword($this->passwordEncoder->hashPassword($user, bin2hex(random_bytes(16))));
                        $user->setSynergyId(0);
                        $user->setLocale('nl');
                    }
                    $institution = $this->findOrCreateInstitution($user, $item['synergyid'], $item['schoolname']);

                    if ($institution) {
                        $user->addInstitution($institution);
                    }

                    $this->entityManager->persist($user);
                    $this->entityManager->flush();
                }
            }
        }

        foreach ($notfound as $key => $value) {
            $this->logger->warning($key . " => not found in SP2");
        }
    }

    private function findOrCreateInstitution($user, $synergyId, $institutionName)
    {
        if (empty($synergyId)) {
        } else {
            $institution = $this->ir->findInstitutionBySynergy($synergyId);
            if ($institution) {
                $institution->addUser($user);
            } else {
                $institution = new Institution();
                $institution->setSynergyId($synergyId);
                $institution->setInstitutionName($institutionName);
                $institution->addUser($user);
            }
            $this->entityManager->persist($institution);
            $this->entityManager->flush();

            return $institution;
        }

        return null;
    }
}
