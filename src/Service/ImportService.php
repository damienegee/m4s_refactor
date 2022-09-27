<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Institution;
use App\Entity\Address;
use App\Entity\InstitutionLocation;
use App\Repository\InstitutionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class ImportService
{
    private $entityManager;
    private $passwordEncoder;
    private $importedUser = array();
    private $ur;
    private $ir;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder, UserRepository $ur, InstitutionRepository $ir)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->ur = $ur;
        $this->ir = $ir;
    }

    public function getDataFromSynergy($synergyConnection)
    {
        // select query
        $query = "select * from cicntp with (nolock)
            left join cicmpy with (nolock) on cicmpy.cmp_wwn = cicntp.cmp_wwn
            where cnt_job_desc = 'M4SBeheer' ";

        $statement = $synergyConnection->getConnection()->prepare($query);
        $statement->execute();

        $result = $statement->fetchAll();

        foreach ($result as $item) {
            $user = $this->fetchOrCreateUser($item);

            // save the user
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            array_push($this->importedUser, $user);
        }

        return $result;
    }

    private function fetchOrCreateUser($item)
    {
        //$user = $this->entityManager->getRepository(User::class)->findUserByEmail($item['cnt_email']);
        $user = $this->ur->findUserByEmail($item['cnt_email']);

        if (!$user) {
            $user = new User();
            $user->setName($item['FullName']);
            $user->setEmail($item['cnt_email']);
            $user->setSynergyId($item['cmp_code']);

            // Set their role
            $user->setRoles(['ROLE_USER']);
            // Create a temporary password for the new user and encode it
            $user->setPassword($this->passwordEncoder->hashPassword($user, "S!gnp0st"));
        }

        $this->fetchOrCreateSchoolForUser($user, $item);

        return $user;
    }

    private function fetchOrCreateSchoolForUser(User $user, $item)
    {
        //$institution = $this->entityManager->getRepository(Institution::class)->findInstitutionBySynergy($item['debnr']);
        $institution = $this->ir->findInstitutionBySynergy($item['debnr']);

        if (!$institution) {
            $institution = new Institution();
            $institution->setSynergyId($item['debnr']);
            $institution->setInstitutionName($item['cmp_name']);
            //$institution->setInstitutionNumber($item['cmp_code']);
            $this->createAddressForInstitution($institution, $item);
        }
        $institution->addUser($user);

        $this->entityManager->persist($institution);
        $this->entityManager->flush();
    }

    private function createAddressForInstitution(Institution $institution, $item)
    {
        $institutionLocation = new InstitutionLocation();
        $institutionLocation->setInstitutionName($item['cmp_name']);
        $institutionLocation->setInstitutionNumber($item['cmp_code']);

        $address = new Address();
        $address->setStreet(($item['cmp_fadd1']));
        $address->setNumber(substr($item['cmp_fadd1'], -1, strpos($item['cmp_fadd1'], ' ')));
        $address->setBus($item['cmp_fadd2']);
        $address->setCity(($item['cmp_fcity']) == null ? "" : $item['cmp_fcity']);
        $address->setZipCode(($item['cmp_fpc']) == null ? 0 : $item['cmp_fpc']);
        $this->entityManager->persist($address);
        $this->entityManager->flush();

        //$institution->addAddress($address);
        //$address->setInstitution($institution);
        $institutionLocation->setAddress($address);
        $institutionLocation->setInstitution($institution);

        $this->entityManager->persist($institutionLocation);
        $this->entityManager->flush();
    }
}
