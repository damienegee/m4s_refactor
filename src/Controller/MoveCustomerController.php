<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoveCustomerController extends AbstractController
{
    /**
     * @Route("/move/customer", name="move_customer")
     */
    public function index(): Response
    {
        return $this->render('move_customer/index.html.twig', [
            'controller_name' => 'MoveCustomerController',
        ]);
    }
}
