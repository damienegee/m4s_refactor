<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210706140425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Customer (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(191) NOT NULL, lastname VARCHAR(191) NOT NULL, email VARCHAR(191) NOT NULL, type ENUM(\'EMPLOYEE\', \'ADMINISTRATION\', \'SALES\', \'STUDENT\', \'TEACHER\', \'ICT COORDINATOR\') NOT NULL, schoolLocation_id INT DEFAULT NULL, INDEX IDX_784FEC5F563BDED3 (schoolLocation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Device (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, productnumber VARCHAR(45) DEFAULT NULL, manufacturer VARCHAR(45) DEFAULT NULL, model VARCHAR(45) DEFAULT NULL, motherboardCode VARCHAR(45) DEFAULT NULL, motherboardValue VARCHAR(45) DEFAULT NULL, panelCode VARCHAR(45) DEFAULT NULL, panelValue VARCHAR(45) DEFAULT NULL, adapter VARCHAR(45) DEFAULT NULL, keyboard VARCHAR(45) DEFAULT NULL, panelAssemblyCode VARCHAR(45) DEFAULT NULL, panelAssemblyValue VARCHAR(45) DEFAULT NULL, battery VARCHAR(45) DEFAULT NULL, ssdCode VARCHAR(45) DEFAULT NULL, ssdValue VARCHAR(45) DEFAULT NULL, hddCode VARCHAR(45) DEFAULT NULL, hddValue VARCHAR(45) DEFAULT NULL, topcover VARCHAR(45) DEFAULT NULL, displayBezel VARCHAR(45) DEFAULT NULL, displayBackplate VARCHAR(45) DEFAULT NULL, touchpad VARCHAR(45) DEFAULT NULL, bottomCover VARCHAR(45) DEFAULT NULL, memoryCode VARCHAR(45) DEFAULT NULL, memoryValue VARCHAR(45) DEFAULT NULL, powerbutton VARCHAR(45) DEFAULT NULL, wifiAdapter VARCHAR(45) DEFAULT NULL, wifiAntenna VARCHAR(45) DEFAULT NULL, lcdCable VARCHAR(45) DEFAULT NULL, hinges VARCHAR(45) DEFAULT NULL, webcam VARCHAR(45) DEFAULT NULL, speakers VARCHAR(45) DEFAULT NULL, dcIn VARCHAR(45) DEFAULT NULL, cablekit VARCHAR(45) DEFAULT NULL, dvddrive VARCHAR(45) DEFAULT NULL, usbAudioboard VARCHAR(45) DEFAULT NULL, systemIoBoard VARCHAR(45) DEFAULT NULL, fanHeatsink VARCHAR(45) DEFAULT NULL, bottomDoor VARCHAR(45) DEFAULT NULL, misc LONGTEXT DEFAULT NULL, picture VARCHAR(191) DEFAULT NULL, hotname VARCHAR(191) DEFAULT NULL, label VARCHAR(191) DEFAULT NULL, serialnumber VARCHAR(191) DEFAULT NULL, mac1 VARCHAR(191) DEFAULT NULL, mac2 VARCHAR(191) DEFAULT NULL, productCode VARCHAR(191) DEFAULT NULL, servicemodelId VARCHAR(191) DEFAULT NULL, servicemodelOrder VARCHAR(191) DEFAULT NULL, endoflife VARCHAR(191) DEFAULT NULL, warranty VARCHAR(191) DEFAULT NULL, intOrderId VARCHAR(191) DEFAULT NULL, extOrderTime VARCHAR(191) DEFAULT NULL, extDeliveryTime VARCHAR(191) DEFAULT NULL, deleted TINYINT(1) DEFAULT NULL, schoolLocation_id INT DEFAULT NULL, INDEX IDX_E83B3B89395C3F3 (customer_id), INDEX IDX_E83B3B8563BDED3 (schoolLocation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Customer ADD CONSTRAINT FK_784FEC5F563BDED3 FOREIGN KEY (schoolLocation_id) REFERENCES InstitutionLocation (id)');
        $this->addSql('ALTER TABLE Device ADD CONSTRAINT FK_E83B3B89395C3F3 FOREIGN KEY (customer_id) REFERENCES Customer (id)');
        $this->addSql('ALTER TABLE Device ADD CONSTRAINT FK_E83B3B8563BDED3 FOREIGN KEY (schoolLocation_id) REFERENCES InstitutionLocation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Device DROP FOREIGN KEY FK_E83B3B89395C3F3');
        $this->addSql('DROP TABLE Customer');
        $this->addSql('DROP TABLE Device');
    }
}
