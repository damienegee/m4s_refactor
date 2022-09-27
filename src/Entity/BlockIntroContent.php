<?php

namespace App\Entity;

class BlockIntroContent
{

	public static array $content = array(
		'title_nl' => 'U bent op de Academic Shop, de online winkel van Signpost België',
		'content_nl' => "<p>Speciaal voor de leerlingen van de school werd een sterk aanbod samengesteld met één of meerdere toestellen. Deze toestellen beschikken over een Next Businessday On Site (=NBOS) garantie. Dit betekent dat, bij problemen, een gecertifieerde technieker van Signpost de werkdag nadien het toestel komt herstellen. Op school of tijdens de schoolvakanties bij de leerling thuis. De toestellen zijn gebruiksklaar bij levering en voorzien van alle nodige software, zijn gedekt tegen diefstal (enkel met braak/bedreiging met inbegrip van proces-verbaal van de politie en buiten de schoolpoort) en bevatten een maximale herstelkost voor defecten buiten garantie.<br><br>
							De laptops worden uitgeleverd op school, op het tijdstip dat Signpost en de school hebben afgesproken. Contacteer uw school bij vragen rond deze levering. Bestellingen die worden betaald voor 15 juli 2022 worden uitgeleverd op dat afgesproken tijdstip. Bestellingen die later worden uitgevoerd, worden op een later tijdstip nageleverd.<br><br>
							
							Veel succes dit schooljaar<br>
							Het Signpost-team</p>",
		'title_fr' => 'Bienvenue l’Academic Shop! la boutique en ligne de Signpost Belgique',
		'content_fr' => "<p>Dédiée aux étudiants de l’école, une offre forte a été mise en place avec un ou plusieurs appareils. Ces appareils ont une garantie Next Business day On Site (=NBOS). Cela signifie qu’en cas de problème, un technicien certifié de Signpost réparera l’appareil le jour ouvrable suivant. À l’école ou au domicile de l’élève, et même pendant les vacances scolaires. Les appareils sont prêts à l’emploi à la livraison et équipés de tous les logiciels nécessaires. Ces appareils sont couverts contre le vol (seulement avec effraction incluant un rapport de police et en dehors des portes de l'école) et comprennent un coût de réparation maximal pour les défauts non couverts par la garantie.<br><br>
							Les ordinateurs portables sont livrés à l’école, à l'heure convenue entre Signpost et l'école. Veuillez contacter votre école si vous avez des questions sur cette livraison. Les commandes payées avant le 15 juillet 2022 seront livrées à l’heure convenue. Les commandes passées plus tard seront livrées à une date ultérieure.<br><br>
							
							Bonne rentrée scolaire <br>
							L’équipe Signpost </p>",
		'title_es' => 'Estás en academic Shop, la tienda online de Signpost Belgium',
		'content_es' => "<p>Especialmente para los estudiantes de la escuela, se armó una oferta fuerte con uno o más dispositivos. Estos dispositivos tienen una garantía Next Businessday On Site (=NBOS). Esto significa que, en caso de problemas, un Técnico certificado de Signpost reparará el dispositivo el siguiente día hábil. En la escuela o durante las vacaciones escolares en la casa del estudiante. Los dispositivos están listos para su uso en el momento de la entrega y equipados con todo el software necesario, están cubiertos contra el robo (solo con vómito / amenaza, incluido el informe policial y fuera de la puerta de la escuela) y contienen un costo máximo de reparación para defectos fuera de la garantía.<br><br>
							Las computadoras portátiles se entregarán a la escuela, en el momento en que Signpost y la escuela hayan acordado. Póngase en contacto con su escuela si tiene alguna pregunta sobre esta entrega. Los pedidos que se paguen antes del 15 de julio de 2022 se entregarán a la hora acordada. Las órdenes que se ejecuten más tarde se entregarán en un momento posterior.<br><br>
							
							Buena suerte este año escolar<br>
							Equipo Signpost</p>",
		'title_en' => 'You are on the Academic Shop, the online store of Signpost Belgium',
		'content_en' => "<p>Especially for the students of the school, a strong offer was put together with one or more devices. These devices have a Next Businessday On Site (=NBOS) warranty. This means that, in case of problems, a certified Technician of Signpost will repair the device the following working day. At school or during the school holidays at the student's home. The devices are ready for use upon delivery and equipped with all necessary software, are covered against theft (only with threat including police report and outside the school gate) and contain a maximum repair cost for defects outside warranty.<br><br>
							The laptops will be delivered to school, at the time that Signpost and the school have agreed. Contact your school if you have any questions about this delivery. Orders that are paid before 15 July 2021 will be delivered at that agreed time. Orders that are executed later will be delivered at a later time.<br><br>
							
							Good luck this school year<br>
							The Signpost-team</p>",
		'title_de' => 'Sie befinden sich im Academic Shop, dem Online-Shop von Signpost Belgien',
		'content_de' => "<p>Speziell für die Schüler der Schule wurde ein starkes Angebot mit einem oder mehreren Geräten zusammengestellt. Diese Geräte haben eine Next Businessday On Site (=NBOS) Garantie. Dies bedeutet, dass im Falle von Problemen ein zertifizierter Techniker von Signpost das Gerät am nächsten Werktag repariert. In der Schule oder während der Schulferien beim Schüler zu Hause. Die Geräte sind bei Lieferung einsatzbereit und mit aller notwendigen Software ausgestattet, gegen Diebstahl abgesichert (nur bei Erbrochenem/Bedrohung inklusive Polizeibericht und außerhalb des Schultors) und enthalten einen maximalen Reparaturaufwand für Mängel außerhalb der Gewährleistung.<br><br>
							Die Laptops werden zu dem Zeitpunkt an die Schule geliefert, zu dem Signpost und die Schule vereinbart haben. Wenden Sie sich an Ihre Schule, wenn Sie Fragen zu dieser Lieferung haben. Bestellungen, die vor dem 15. Juli 2022 bezahlt werden, werden zum vereinbarten Zeitpunkt geliefert. Bestellungen, die später ausgeführt werden, werden zu einem späteren Zeitpunkt geliefert.<br><br>
							
							Viel Glück in diesem Schuljahr<br>
							Het Signpost-team</p>",
		);

	public static function getContent(): array
	{
		return self::$content;
	}

}