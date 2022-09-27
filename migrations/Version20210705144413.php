<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210705144413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Customer (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(191) NOT NULL, lastname VARCHAR(191) NOT NULL, email VARCHAR(191) NOT NULL, type enum(\'EMPLOYEE\', \'ADMINISTRATION\', \'SALES\', \'STUDENT\', \'TEACHER\', \'ICT COORDINATOR\'), schoolLocation_id INT DEFAULT NULL, INDEX IDX_784FEC5F563BDED3 (schoolLocation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Customer ADD CONSTRAINT FK_784FEC5F563BDED3 FOREIGN KEY (schoolLocation_id) REFERENCES InstitutionLocation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE Customer');
    }
}
