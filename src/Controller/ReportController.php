<?php

namespace App\Controller;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\isNull;

class ReportController extends AbstractController
{
	/**
	 * @Route("/report", name="report.csv", methods="POST")
	 */
	public function index(Request $request): Response
	{
		$data = $request->get('csvexport');
		$type = $request->get('type');
		$data = json_decode($data, true);

		$phpExcelObject = new Spreadsheet();

		$sheet = $phpExcelObject->setActiveSheetIndex(0);

		if ($type === 'koop') {
			$sheet->setCellValue('A1', 'Voornaam gebruiker');
			$sheet->setCellValue('B1', 'Naam gebruiker');
			$sheet->setCellValue('C1', 'Volgnummer webshop');
			$sheet->setCellValue('D1', 'Datum bestelling');
			$sheet->setCellValue('E1', 'Besteld toestel');
			$sheet->setCellValue('F1', 'Payment received');
			$sheet->setCellValue('G1', 'Leerling');
			$sheet->setCellValue('H1', 'Leerjaar');
			$sheet->setCellValue('I1', 'Ouder Voornaam');
			$sheet->setCellValue('J1', 'Ouder Naam');
			$sheet->setCellValue('K1', 'Email');
			$sheet->setCellValue('L1', 'Telefoon');
			$sheet->setCellValue('M1', 'Adres');
			$sheet->setCellValue('N1', 'Postcode');
			$sheet->setCellValue('O1', 'Gemeente');

			$counter = 2;
			foreach ($data as $value) {
				$sheet->setCellValue('A' . $counter, $value['voornaam_student']);
				$sheet->setCellValue('B' . $counter, $value['naam_student']);
				$sheet->setCellValue('C' . $counter, $value['increment_id']);
				$sheet->setCellValue('D' . $counter, $value['created_at']);
				$sheet->setCellValue('E' . $counter, $value['name']);
				$sheet->setCellValue('F' . $counter, $value['payment_date']);
				$sheet->setCellValue('G' . $counter, $value['leerling_nummer']);
				$sheet->setCellValue('H' . $counter, $value['leerjaar']);
				$sheet->setCellValue('I' . $counter, $value['customer_firstname']);
				$sheet->setCellValue('J' . $counter, $value['customer_lastname']);
				$sheet->setCellValue('K' . $counter, $value['customer_email']);
				$sheet->setCellValue('L' . $counter, $value['telephone']);
				$sheet->setCellValue('M' . $counter, $value['street']);
				$sheet->setCellValue('N' . $counter, $value['postcode']);
				$sheet->setCellValue('O' . $counter, $value['city']);
				$counter++;
			}
		} elseif ($type === 'huur') {
			$sheet->setCellValue('A1', 'Voornaam gebruiker');
			$sheet->setCellValue('B1', 'Naam gebruiker');
			$sheet->setCellValue('C1', 'Contractvolgnummer');
			$sheet->setCellValue('D1', 'Contract opgemaakt');
			$sheet->setCellValue('E1', 'Contract ontvangen');
			$sheet->setCellValue('F1', 'Voorschot ontvangen');
			$sheet->setCellValue('G1', 'Besteld toestel');
			$sheet->setCellValue('H1', 'School referentie');
			$sheet->setCellValue('I1', 'Leerjaar');
			$sheet->setCellValue('J1', 'Ouder');
			$sheet->setCellValue('K1', 'Ouder2');
			$sheet->setCellValue('L1', 'Email');
			$sheet->setCellValue('M1', 'Email2');
			$sheet->setCellValue('N1', 'Telefoon');
			$sheet->setCellValue('O1', 'Telefoon2');
			$sheet->setCellValue('P1', 'Adres');
			$sheet->setCellValue('Q1', 'Postcode');
			$sheet->setCellValue('R1', 'Gemeente');

			$counter = 2;
			foreach ($data as $value) {
				$sheet->setCellValue('A' . $counter, $value['VoornaamLeerling']);
				$sheet->setCellValue('B' . $counter, $value['NaamLeerling']);
				$sheet->setCellValue('C' . $counter, $value['ContractVolgnummer']);
				$sheet->setCellValue('D' . $counter, $value['DatumContractopgemaakt']);
				$sheet->setCellValue('E' . $counter, $value['MethodeContractOntvangen'] . ' (' . $value['DatumContractOntvangen'] . ')');
				$sheet->setCellValue('F' . $counter, $value['MethodeVoorschotBetaald'] . ' (' . $value['DatumVoorschotOntvangen'] . ')');
				$sheet->setCellValue('G' . $counter, $value['NaamToestel'] . ' (' . $value['OmschrijvingToestel'] . ')');
				$sheet->setCellValue('H' . $counter, $value['ReferentieSchool']);
				$sheet->setCellValue('I' . $counter, $value['Leerjaar']);
				$sheet->setCellValue('J' . $counter, $value['NaamOuder']);
				$sheet->setCellValue('K' . $counter, $value['NaamOuder2']);
				$sheet->setCellValue('L' . $counter, $value['Email1']);
				$sheet->setCellValue('M' . $counter, $value['Email2']);
				$sheet->setCellValue('N' . $counter, $value['Tel1']);
				$sheet->setCellValue('O' . $counter, $value['Tel2']);
				$sheet->setCellValue('P' . $counter, $value['StraatNr']);
				$sheet->setCellValue('Q' . $counter, $value['Postcode']);
				$sheet->setCellValue('R' . $counter, $value['Gemeente']);
				$counter++;
			}
		} else if ($type === 'fieldservices') {
			$sheet->setCellValue('A1', 'Case nummer');
			$sheet->setCellValue('B1', 'Leerling');
			$sheet->setCellValue('C1', 'Serienummer');
			$sheet->setCellValue('D1', 'Titel');
			$sheet->setCellValue('E1', 'Probleem');
			$sheet->setCellValue('F1', 'Status');
			$sheet->setCellValue('G1', 'Aangemaakt');
			$sheet->setCellValue('H1', 'Gewijzigd');
			$sheet->setCellValue('I1', 'Vestiging');

			$counter = 2;
			foreach ($data as $value) {
				$sheet->setCellValue('A' . $counter, 'FS' . $value['id']);
				$sheet->setCellValue('B' . $counter, $value['firstname'] . ' ' . $value['lastname']);
				$sheet->setCellValue('C' . $counter, $value['serial']);
				$sheet->setCellValue('D' . $counter, $value['title']);
				$sheet->setCellValue('E' . $counter,  $value['category']. ' / '.$value['problem'] );
				$sheet->setCellValue('F' . $counter, $value['status']);
				$sheet->setCellValue('G' . $counter, $value['created']);
				$sheet->setCellValue('H' . $counter, $value['modified']);
				$sheet->setCellValue('I' . $counter, $value['city']);
				$counter++;
			}
		} else if ($type === 'inventory') {
			$sheet->setCellValue('A1', 'Label');
			$sheet->setCellValue('B1', 'Productnummer');
			$sheet->setCellValue('C1', 'Serienummer');
			$sheet->setCellValue('D1', 'Model');
			$sheet->setCellValue('E1', 'Gebruiker');
			$sheet->setCellValue('F1', 'Functie');

			$counter = 2;

			foreach ($data as $value) {
				$sheet->setCellValue('A' . $counter, $value['label']);
				$sheet->setCellValue('B' . $counter, $value['productnumber']);
				$sheet->setCellValue('C' . $counter, $value['serialnumber']);
				$sheet->setCellValue('D' . $counter, $value['model']);
				$sheet->setCellValue('E' . $counter, $value['firstname'] . ' ' . $value['lastname']);
				$sheet->setCellValue('F' . $counter, $value['type']);
				$counter++;
			}
		} else if ($type === 'hireshop') {
			$sheet->setCellValue('A1', 'Voornaam gebruiker');
			$sheet->setCellValue('B1', 'Naam gebruiker');
			$sheet->setCellValue('C1', 'Contractvolgnummer');
			$sheet->setCellValue('D1', 'Contract opgemaakt');
			$sheet->setCellValue('E1', 'Contract ontvangen');
			$sheet->setCellValue('F1', 'Voorschot ontvangen');
			$sheet->setCellValue('G1', 'Besteld toestel');
			$sheet->setCellValue('H1', 'Leerjaar');
			$sheet->setCellValue('I1', 'Ouder');
			$sheet->setCellValue('J1', 'Ouder2');
			$sheet->setCellValue('K1', 'Email');
			$sheet->setCellValue('L1', 'Email2');
			$sheet->setCellValue('M1', 'Telefoon');
			$sheet->setCellValue('N1', 'Telefoon2');
			$sheet->setCellValue('O1', 'Adres');
			$sheet->setCellValue('P1', 'Postcode');
			$sheet->setCellValue('Q1', 'Gemeente');
			$sheet->setCellValue('R1', 'Type');
			$sheet->setCellValue('S1', 'Betaling');
			$sheet->setCellValue('T1', 'Status');

			$counter = 2;
			foreach ($data as $value) {
				$sheet->setCellValue('A' . $counter, $value['VoornaamLeerling']);
				$sheet->setCellValue('B' . $counter, $value['NaamLeerling']);
				$sheet->setCellValue('C' . $counter, $value['ContractVolgnummer']);
				$sheet->setCellValue('D' . $counter, $value['DatumContract']);
				$sheet->setCellValue('E' . $counter, $value['MethodeContract'] . ' (' . $value['DatumOntvangen'] . ')');
				$sheet->setCellValue('F' . $counter, $value['MethodeBetaald'] . ' (' . $value['DatumBetaald'] . ')');
				$sheet->setCellValue('G' . $counter, $value['NaamToestel'] . ' (' . $value['OmschrijvingToestel'] . ')');
				$sheet->setCellValue('H' . $counter, $value['Leerjaar']);
				$sheet->setCellValue('I' . $counter, $value['NaamOuder']);
				$sheet->setCellValue('J' . $counter, $value['NaamOuder2']);
				$sheet->setCellValue('K' . $counter, $value['Email1']);
				$sheet->setCellValue('L' . $counter, $value['Email2']);
				$sheet->setCellValue('M' . $counter, $value['Tel1']);
				$sheet->setCellValue('N' . $counter, $value['Tel2']);
				$sheet->setCellValue('O' . $counter, $value['StraatNr']);
				$sheet->setCellValue('P' . $counter, $value['Postcode']);
				$sheet->setCellValue('Q' . $counter, $value['Gemeente']);
				$sheet->setCellValue('R' . $counter, $value['type']);
				$sheet->setCellValue('S' . $counter, $value['betalingok']);
				$sheet->setCellValue('T' . $counter, $value['status']);
				$counter++;
			}
		} else if ($type === 'internaltickets') {
			$sheet->setCellValue('A1', 'Case nummer');
			$sheet->setCellValue('B1', 'Leerling');
			$sheet->setCellValue('C1', 'Serienummer');
			$sheet->setCellValue('D1', 'Titel');
			$sheet->setCellValue('E1', 'Probleem');
			$sheet->setCellValue('F1', 'Status');
			$sheet->setCellValue('G1', 'Aangemaakt');
			$sheet->setCellValue('H1', 'Gewijzigd');
			$sheet->setCellValue('I1', 'Vestiging');

			$counter = 2;
			foreach ($data as $value) {
				$sheet->setCellValue('A' . $counter, $value['id']);
				$sheet->setCellValue('B' . $counter, $value['firstname'] . ' ' . $value['lastname']);
				$sheet->setCellValue('C' . $counter, $value['serialnumber']);
				$sheet->setCellValue('D' . $counter, $value['problem']);
				$sheet->setCellValue('E' . $counter, strip_tags($value['description']));
				$sheet->setCellValue('F' . $counter, $value['state']);
				$sheet->setCellValue('G' . $counter, $value['created_at']);
				$sheet->setCellValue('H' . $counter, $value['modified_at']);
				$sheet->setCellValue('I' . $counter, $value['city']);
				$counter++;
			}
		} else if ($type === 'webshopAndRentOrders') {
			$sheet->setCellValue('A1', 'Type');
			$sheet->setCellValue('B1', 'Status');
			$sheet->setCellValue('C1', 'Contract');
			$sheet->setCellValue('D1', 'Student ID');
			$sheet->setCellValue('E1', 'Voornaaam');
			$sheet->setCellValue('F1', 'Achternaam');
			$sheet->setCellValue('G1', 'E-mail');
			$sheet->setCellValue('H1', 'Telefoonnummer');
			$sheet->setCellValue('I1', 'Naam toestel');
			$sheet->setCellValue('J1', 'Koopprijs');
			$sheet->setCellValue('K1', 'Waarborg');
			$sheet->setCellValue('L1', 'Huurprijs');
			$sheet->setCellValue('M1', 'Gestructureerde mededeling');
			$sheet->setCellValue('N1', 'field_title');
			$sheet->setCellValue('O1', 'value');

			$counter = 2;

			foreach ($data as $value) {
				$sheet->setCellValue('A' . $counter, $value['type']);
				$sheet->setCellValue('B' . $counter, $value['status']);

				if ($value['contract_signed'] == "ADOBE_SIGN" || $value['contract_signed'] == "Manueel") {
					$sheet->setCellValue('C' . $counter, "Getekend");
				} elseif ($value['type'] == "Webshop") {
					$sheet->setCellValue('C' . $counter, "/");
				} else {
					$sheet->setCellValue('C' . $counter, "Niet getekend");
				}

				$sheet->setCellValue('D' . $counter, $value['studentID']);
				$sheet->setCellValue('E' . $counter, $value['studentFirstName']);
				$sheet->setCellValue('F' . $counter, $value['studentLastName']);
				$sheet->setCellValue('G' . $counter, $value['email']);
				$sheet->setCellValue('H' . $counter, $value['phone']);

				if ($value['type'] == "Webshop") {
					if (isNull($value['deviceIDShop'])) {
						$sheet->setCellValue('I' . $counter, $value['deviceManufacturer'] . ' ' . $value['deviceModel']);
					} else {
						$sheet->setCellValue('I' . $counter, $value['heddeviceManufacturer'] . ' ' . $value['heddeviceModel']);
					}
				} elseif ($value['type'] == "Leermiddel") {
					$sheet->setCellValue('I' . $counter, $value['NaamToestel']);
				}

				$sheet->setCellValue('J' . $counter, $value['total']);
				$sheet->setCellValue('K' . $counter, $value['Waarborg'] * $value['Huurtermijn']);
				$sheet->setCellValue('L' . $counter, $value['Huurprijs'] * $value['Huurtermijn']);

				if (empty($value['ogm'])) {
					$sheet->setCellValue('M' . $counter, $value['ogm']);
				} else {
					$sheet->setCellValue('M' . $counter, "'" . $value['ogm']);
				}

				$sheet->setCellValue('N' . $counter, $value['field_title']);
				$sheet->setCellValue('N' . $counter, $value['value']);

				$counter++;
			}
		}

		$writer = new Xlsx($phpExcelObject);

		$response =  new StreamedResponse(
			function () use ($writer) {
				$writer->save('php://output');
			}
		);
		$response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$response->headers->set('Content-Disposition', 'attachment;filename="Report.xlsx"');
		$response->headers->set('Cache-Control', 'max-age=0');

		return $response;
	}
}
