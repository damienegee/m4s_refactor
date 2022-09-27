<?php

namespace App\Controller\Admin;

use App\Service\ImportService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *  @Route("/admin")
 */
class ImportDataController extends AbstractController
{

    private $is;
    private $doctrine;

    public function __construct(ImportService $is, ManagerRegistry $doctrine)
    {
        $this->is = $is;
        $this->doctrine = $doctrine;
    }
    /**
     * @Route("/import/data", name="admin_import_data")
     */
    public function index(): Response
    {
        // get connection
        $synergyConnection = $this->doctrine->getManager('synergy');
        $data = $this->is->getDataFromSynergy($synergyConnection);

        return $this->render('admin/import_data/index.html.twig', [
            'data' => $data,
        ]);
    }
}
