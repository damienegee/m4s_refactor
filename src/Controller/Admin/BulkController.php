<?php

namespace App\Controller\Admin;

use App\Entity\Institution;
use App\Form\ImportFromCsvType;
use App\Repository\InstitutionRepository;
use App\Service\AssiginDevicesToUsersService;
use App\Service\CustomerImportService;
use App\Service\DeviceImportService;
use App\Service\ExtraDeviceImportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @Route("/admin")
 */
class BulkController extends AbstractController
{
    private $dis;
    private $cis;
    private $eis;
    private $adu;
    private $ir;

    public function __construct(DeviceImportService $dis, CustomerImportService $cis, ExtraDeviceImportService $eis, AssiginDevicesToUsersService $adu, InstitutionRepository $ir)
    {
        $this->dis = $dis;
        $this->cis = $cis;
        $this->eis = $eis;
        $this->adu = $adu;
        $this->ir = $ir;
    }

    public function index(): Response
    {
        return $this->render('admin/bulk/index.html.twig');
    }

    /**
     * @Route("/importdevices", name="admin_bulk_devices")
     */
    public function importDevices(Request $request): Response
    {
        $importErrors = array();
        $fileDir = $this->getParameter('csv_directory');
        $cookies = $request->cookies;
        if ($cookies->has('institution_id') && $cookies->get('institution_id') !== '') {
            /** @var Institution $institution */
            $institution = $this->ir->find($cookies->get('institution_id'));
            if (!$institution) {
                throw new \Exception("institution not found");
            }
            $form = $this->createForm(ImportFromCsvType::class, null, array(
                'synergy' => $institution->getSynergyId()
            ));
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->get('schoollocation')->getData() === NULL) {
                    $this->addFlash("danger", "select a location");
                    return $this->redirectToRoute('admin_bulk_devices');
                }
                $this->dis->setLocationId($form->get('schoollocation')->getData());
                //upload the file
                /** @var UploadedFile $file */
                $file = $form->get('file')->getData();
                $filename = md5(uniqid()) . '.csv';
                $file->move(
                    $fileDir,
                    $filename
                );
                //execute the import
                $this->dis->execute($fileDir, $filename);
                // get errors if any
                $importErrors = $this->dis->getAllErrors();

                // delete the file when done
                $this->deleteFile($fileDir, $filename);
            }
            return $this->render('admin/bulk/index.device.html.twig', [
                'form' => $form->createView(),
                "errors" => $importErrors,
                "importWhat" => "devices"
            ]);
        } else {
            $this->addFlash("info", "select an Institution first");
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/importcustomers", name="admin_bulk_customers")
     */
    public function importCustomers(Request $request): Response
    {
        $importErrors = array();
        $fileDir = $this->getParameter('csv_directory');
        $cookies = $request->cookies;
        if ($cookies->has('institution_id') && $cookies->get('institution_id') !== '') {
            $institution = $this->ir->find($cookies->get('institution_id'));
            if (!$institution) {
                throw new \Exception("institution not found");
            }
            $form = $this->createForm(ImportFromCsvType::class, null, array(
                'synergy' => $institution->getSynergyId()
            ));
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->get('schoollocation')->getData() === NULL) {
                    $this->addFlash("danger", "select a location");
                    return $this->redirectToRoute('admin_bulk_customers');
                }

                $this->cis->setLocationId($form->get('schoollocation')->getData());
                //upload the file
                /** @var UploadedFile $file */
                $file = $form->get('file')->getData();
                $filename = md5(uniqid()) . '.csv';
                $file->move(
                    $fileDir,
                    $filename
                );
                //execute the import
                $this->cis->execute($fileDir, $filename);
                // get errors if any
                $importErrors = $this->cis->getAllErrors();

                // delete the file when done
                $this->deleteFile($fileDir, $filename);
            }
            return $this->render('admin/bulk/index.customer.html.twig', [
                'form' => $form->createView(),
                "errors" => $importErrors,
                "importWhat" => "customers"
            ]);
        } else {
            $this->addFlash("info", "select an Institution first");
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/importextradevices", name="admin_bulk_extradevices")
     */
    public function importExtraDevices(Request $request): Response
    {
        $importErrors = array();
        $fileDir = $this->getParameter('csv_directory');
        $cookies = $request->cookies;
        if ($cookies->has('institution_id') && $cookies->get('institution_id') !== '') {
            $institution = $this->ir->find($cookies->get('institution_id'));
            if (!$institution) {
                throw new \Exception("institution not found");
            }
            $form = $this->createForm(ImportFromCsvType::class, null, array(
                'synergy' => $institution->getSynergyId()
            ));
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->get('schoollocation')->getData() === NULL) {
                    $this->addFlash("danger", "select a location");
                    return $this->redirectToRoute('admin_bulk_extradevices');
                }
                $this->eis->setLocationId($form->get('schoollocation')->getData());
                //upload the file
                /** @var UploadedFile $file */
                $file = $form->get('file')->getData();
                $filename = md5(uniqid()) . '.csv';
                $file->move(
                    $fileDir,
                    $filename
                );
                //execute the import
                $this->eis->execute($fileDir, $filename);
                // get errors if any
                $importErrors = $this->eis->getAllErrors();

                // delete the file when done
                $this->deleteFile($fileDir, $filename);
            }
            return $this->render('admin/bulk/index.extradevice.html.twig', [
                'form' => $form->createView(),
                "errors" => $importErrors,
                "importWhat" => "extradevices"
            ]);
        } else {
            $this->addFlash("info", "select an Institution first");
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/assigndevicetousers", name="admin_bulk_assign")
     */
    public function assignUserToDevice(Request $request): Response
    {
        $importErrors = array();
        $fileDir = $this->getParameter('csv_directory');
        $cookies = $request->cookies;
        if ($cookies->has('institution_id') && $cookies->get('institution_id') !== '') {
            $institution = $this->ir->find($cookies->get('institution_id'));
            if (!$institution) {
                throw new \Exception("institution not found");
            }
            $form = $this->createForm(ImportFromCsvType::class, null, array(
                'synergy' => $institution->getSynergyId(),
                'extradevice' => true
            ));
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->adu->setLocationId($form->get('schoollocation')->getData());
                //upload the file
                /** @var UploadedFile $file */
                $file = $form->get('file')->getData();
                $filename = md5(uniqid()) . '.csv';
                $file->move(
                    $fileDir,
                    $filename
                );
                $notByodDevice = $form->get('extradevice')->getData();
                //execute the import
                $this->adu->execute($fileDir, $filename, $notByodDevice);
                // get errors if any
                $importErrors = $this->adu->getAllErrors();

                // delete the file when done
                $this->deleteFile($fileDir, $filename);
            }
            return $this->render('admin/bulk/index.assign.html.twig', [
                'form' => $form->createView(),
                "importWhat" => "assign",
                "errors" => $importErrors,
            ]);
        } else {
            $this->addFlash("info", "select an Institution first");
            return $this->redirectToRoute('home');
        }
    }

    private function deleteFile($fileDir, $filename)
    {
        $fileSystem = new Filesystem();
        try {
            $fileSystem->remove($fileDir . '/' . $filename);
        } catch (\Exception $th) {
            throw new \Exception($th->getMessage());
        }
    }
}
