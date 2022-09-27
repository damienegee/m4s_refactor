<?php

namespace App\Controller\Admin;

use App\Form\DeviceImageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeviceImageController extends AbstractController
{
    /**
     * @Route("/admin/device/image", name="admin_device_image")
     */
    public function index(Request $request): Response
    {
        $imageDir = $this->getParameter('model_directory');


        $form = $this->createForm(DeviceImageType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            $devicemodel = $form->get('devicemodel')->getData();
            $file = $devicemodel . '.' . $image->guessExtension();
            $image->move($imageDir, $file);
        }

        $finder = Finder::create()
                    ->in($imageDir)
                    ->depth(0);
        $files = \iterator_to_array($finder, true);
        
        return $this->render('admin/device_image/index.html.twig', [
            'form' => $form->createView(),
            'files' => $files
        ]);
    }

    /**
     * @Route("admin/device/image/delete", name="admin_device_image_delete")
     */
    public function removeImageFromServer(Request $request): Response {
        $imageToDelete = $request->get('path');

        $fileSystem = new Filesystem();
        try {
            $fileSystem->remove($imageToDelete);
        } catch (\Exception $th) {
            throw new \Exception($th->getMessage());
        }

        return $this->redirectToRoute('admin_device_image');
    }
}
