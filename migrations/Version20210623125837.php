<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210623125837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Images (id INT AUTO_INCREMENT NOT NULL, loans_id INT DEFAULT NULL, name VARCHAR(191) NOT NULL, returnedLoan_id INT DEFAULT NULL, INDEX IDX_E7B3BB5C9AB85012 (loans_id), INDEX IDX_E7B3BB5C44BC5E (returnedLoan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Loan (id INT AUTO_INCREMENT NOT NULL, startdate DATE NOT NULL, user VARCHAR(191) NOT NULL, enddate DATE DEFAULT NULL, remark LONGTEXT DEFAULT NULL, signature LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ReturnedLoan (id INT AUTO_INCREMENT NOT NULL, returneddate DATE NOT NULL, remarks LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Images ADD CONSTRAINT FK_E7B3BB5C9AB85012 FOREIGN KEY (loans_id) REFERENCES Loan (id)');
        $this->addSql('ALTER TABLE Images ADD CONSTRAINT FK_E7B3BB5C44BC5E FOREIGN KEY (returnedLoan_id) REFERENCES ReturnedLoan (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Images DROP FOREIGN KEY FK_E7B3BB5C9AB85012');
        $this->addSql('ALTER TABLE Images DROP FOREIGN KEY FK_E7B3BB5C44BC5E');
        $this->addSql('DROP TABLE Images');
        $this->addSql('DROP TABLE Loan');
        $this->addSql('DROP TABLE ReturnedLoan');
    }
}
