<?php

namespace App\Service;

// use App\Entity\Customer;
// use App\Repository\InstitutionLocationRepository;
// use Doctrine\ORM\EntityManagerInterface;
// use Doctrine\Persistence\ConnectionRegistry;
// use Doctrine\Persistence\ManagerRegistry;

/**
 * This is only to use for dev purpose. If you don't have any API you can import data locally
 */
class MigrateM4SClient
{

    private $mr;
    private $ilr;
    private $em;
    private $query = "
        SELECT DISTINCT
            client.firstname as firstname,
            client.lastname as lastname,
            client.mail as email,
            client.type as type,
            campus.name as institutionname,
            campus.institution_number as institutionnumber
        FROM
            client
        INNER JOIN
            campus
        ON
            campus.id = client.campus_id
        WHERE campus.deleted != 1
        AND client.deleted != 1
    ";

    public function __construct()
    {
        // $this->mr = $mr;
        // $this->ilr = $ilr;
        // $this->em = $em;
    }

    public function migrateM4SClient()
    {
        // /** @var ConnectionRegistry $conn */
        // $conn = $this->mr->getManager('byod_m4s');
        // $statement = $conn->getConnection()->prepare($this->query);

        // $resultSet = $statement->executeQuery();

        // $results = $resultSet->fetchAllAssociative();

        // foreach($results as $item) {

        //     $institutionLocation = null;
        //     if($item['institutionnumber']) {
        //         $institutionLocation = $this->ilr->findByInstitutionNumber($item['institutionnumber']);
        //     } else {
        //         try {
        //             $institutionLocation = $this->ilr->findByInstitutionName($item['institutionname']);
        //         } catch (\Exception $e) {

        //             throw new \Exception($e->getMessage());
        //         }
        //     }

        //     $customer = new Customer();
        //     $customer->setEmail($item['email']);
        //     $customer->setFirstname($item['firstname']);
        //     $customer->setLastname($item['lastname']);

        //     $type = strtoupper($item['type']) === strtoupper('administrative') ? 'ADMINISTRATION' : strtoupper($item['type']);
        //     $customer->setType($type);
        //     $customer->setSchoolLocation($institutionLocation);

        //     $this->em->persist($customer);
        //     $this->em->flush();
        // }
    }
}
