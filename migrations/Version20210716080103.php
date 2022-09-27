<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210716080103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE MoveDeviceLog (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, deviceId INT NOT NULL, deviceSerial VARCHAR(45) NOT NULL, fromLocationId INT NOT NULL, fromLocationName VARCHAR(191) NOT NULL, toLocationId INT NOT NULL, toLocationName VARCHAR(191) NOT NULL, whenMoved DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE MoveDeviceLog ADD CONSTRAINT FK_AF25A966A76ED395 FOREIGN KEY (user_id) REFERENCES User (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE MoveDeviceLog');
    }
}
