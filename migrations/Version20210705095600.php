<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210705095600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE InstitutionLocation (id INT AUTO_INCREMENT NOT NULL, address_id INT DEFAULT NULL, institution_id INT DEFAULT NULL, name VARCHAR(191) NOT NULL, institutionName VARCHAR(191) NOT NULL, institutionNumber INT DEFAULT NULL, UNIQUE INDEX UNIQ_91DA8A66F5B7AF75 (address_id), INDEX IDX_91DA8A6610405986 (institution_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE InstitutionLocation ADD CONSTRAINT FK_91DA8A66F5B7AF75 FOREIGN KEY (address_id) REFERENCES Address (id)');
        $this->addSql('ALTER TABLE InstitutionLocation ADD CONSTRAINT FK_91DA8A6610405986 FOREIGN KEY (institution_id) REFERENCES Institution (id)');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F8110405986');
        $this->addSql('DROP INDEX IDX_C2F3561D10405986 ON address');
        $this->addSql('ALTER TABLE address DROP institution_id');
        $this->addSql('ALTER TABLE institution DROP institution_number');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE InstitutionLocation');
        $this->addSql('ALTER TABLE Address ADD institution_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Address ADD CONSTRAINT FK_D4E6F8110405986 FOREIGN KEY (institution_id) REFERENCES institution (id)');
        $this->addSql('CREATE INDEX IDX_C2F3561D10405986 ON Address (institution_id)');
        $this->addSql('ALTER TABLE Institution ADD institution_number INT NOT NULL');
    }
}
