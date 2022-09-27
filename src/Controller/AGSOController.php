<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Service\SP2ApiService;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class AGSOController extends AbstractController
{
    // private $apiService;
    private $api;
    private $cache;

    public function __construct(SP2ApiService $api, CacheInterface $cache)
    {
        $this->api = $api;
        $this->cache = $cache;
    }
    /**
     * @Route("/agso", name="agso")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $cachKey = 'AGSO';
        if (in_array('ROLE_AGSO', $user->getRoles()) || in_array('ROLE_ADMIN', $user->getRoles())) {
            $data = $this->cache->get($cachKey, function (ItemInterface $item) use ($request) {
                $item->expiresAfter(3600);
                //'sort_field' => 'orderId', 'sort_direction' => 'DESC'
                $sortField = $request->get('sort');
                $sortDirection = $request->get('direction');

                return $this->api->getAGSOWebshopOrders($sortField, $sortDirection);
            });

            return $this->render('agso/index.html.twig', [
                'data' => $data,
            ]);
        } else {
            return $this->redirectToRoute('accessdenied');
        }
    }
}
