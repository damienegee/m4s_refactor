<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210624113413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE loan ADD school_id INT NOT NULL');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_65E1A23DC32A47EE FOREIGN KEY (school_id) REFERENCES Institution (id)');
        $this->addSql('CREATE INDEX IDX_65E1A23DC32A47EE ON loan (school_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Loan DROP FOREIGN KEY FK_65E1A23DC32A47EE');
        $this->addSql('DROP INDEX IDX_65E1A23DC32A47EE ON Loan');
        $this->addSql('ALTER TABLE Loan DROP school_id');
    }
}
