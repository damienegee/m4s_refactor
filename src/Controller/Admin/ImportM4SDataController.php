<?php

namespace App\Controller\Admin;

use App\Service\MigrateM4SClient;
use App\Service\MigrateM4SDevice;
use App\Service\MigrateM4SUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImportM4SDataController extends AbstractController
{
    private $m4sUser;
    private $m4sclient;
    private $m4sdevice;

    public function __construct(MigrateM4SUser $m4sUser, MigrateM4SClient $m4sclient, MigrateM4SDevice $m4sdevice)
    {
        $this->m4sUser = $m4sUser;
        $this->m4sclient = $m4sclient;
        $this->m4sdevice = $m4sdevice;
    }
    /**
     * @Route("/admin/importm4susers", name="admin_import_m4s_users")
     */
    public function importm4susers(): Response
    {
        $this->m4sUser->migrateM4SUser();
        return $this->render('admin/import_m4s_data/index.html.twig');
    }

    /**
     * @Route("/admin/importm4sclients", name="admin_import_m4s_clients")
     */
    public function importm4sclient(): Response {
        $this->m4sclient->migrateM4SClient();
        return $this->render('admin/import_m4s_data/index.html.twig');
    }

    /**
     * @Route("/admin/importm4sdevices", name="admin_import_m4s_devices")
     */
    public function importm4sdevices(): Response {
        $this->m4sdevice->migrateM4SDevice();
        return $this->render('admin/import_m4s_data/index.html');
    }

}
