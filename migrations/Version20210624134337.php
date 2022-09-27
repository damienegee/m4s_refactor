<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210624134337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE returnedloan ADD loan_id INT NOT NULL');
        $this->addSql('ALTER TABLE returnedloan ADD CONSTRAINT FK_8A5208EECE73868F FOREIGN KEY (loan_id) REFERENCES Loan (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8A5208EECE73868F ON returnedloan (loan_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ReturnedLoan DROP FOREIGN KEY FK_8A5208EECE73868F');
        $this->addSql('DROP INDEX UNIQ_8A5208EECE73868F ON ReturnedLoan');
        $this->addSql('ALTER TABLE ReturnedLoan DROP loan_id');
    }
}
