<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210716134621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_65E1A23DC32A47EE');
        $this->addSql('DROP INDEX IDX_65E1A23DC32A47EE ON loan');
        $this->addSql('ALTER TABLE loan ADD deviceId INT NOT NULL, DROP device, CHANGE school_id schoollocationId INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Loan ADD school_id INT NOT NULL, ADD device VARCHAR(191) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, DROP schoollocationId, DROP deviceId');
        $this->addSql('ALTER TABLE Loan ADD CONSTRAINT FK_65E1A23DC32A47EE FOREIGN KEY (school_id) REFERENCES institution (id)');
        $this->addSql('CREATE INDEX IDX_65E1A23DC32A47EE ON Loan (school_id)');
    }
}
