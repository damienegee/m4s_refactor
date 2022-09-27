<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GoogleController extends AbstractController
{
    /**
     * Link to this controller to start the "connect" process
     * @Route("/connect/google", name="connect_google")
     */
    public function connect(ClientRegistry $clientRegistry)
    {
        //Google are now blocking requests with an approval_prompt sent.
        // So you have to set the promt to consent
        return $clientRegistry
            ->getClient('google')
            ->redirect([], ['prompt' => 'consent']);
    }

    /**
     * After goint to Azure, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     * 
     * @Route("/connect/google/check", name="connect_google_check")
     */
    public function connectCheck(Request $request, ClientRegistry $clientRegistry)
    {
        if (!$this->getUser()) {
            return new JsonResponse(array('status' => false, 'message' => "User not found!"));
        } else {
            return $this->redirectToRoute('root');
        }
    }
}
