<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoveDeviceController extends AbstractController
{
    /**
     * @Route("/move/device", name="move_device")
     */
    public function index(): Response
    {
        return $this->render('move_device/index.html.twig', [
            'controller_name' => 'MoveDeviceController',
        ]);
    }
}
