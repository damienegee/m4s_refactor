<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210719140652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE MoveCustomerLog (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, customerId INT NOT NULL, customerName VARCHAR(191) NOT NULL, fromLocationId INT NOT NULL, fromLocationName VARCHAR(191) NOT NULL, toLocationId INT NOT NULL, toLocationName VARCHAR(191) NOT NULL, whenMoved DATETIME NOT NULL, INDEX IDX_3E329FD5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE MoveCustomerLog ADD CONSTRAINT FK_3E329FD5A76ED395 FOREIGN KEY (user_id) REFERENCES User (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE MoveCustomerLog');
    }
}
