<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210715122354 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE institutionlocation DROP FOREIGN KEY FK_91DA8A66F5B7AF75');
        $this->addSql('CREATE TABLE ExtraDevice (id INT AUTO_INCREMENT NOT NULL, m4sCustomerId INT DEFAULT NULL, m4sSchoollocationId INT NOT NULL, productnumber VARCHAR(45) DEFAULT NULL, manufacturer VARCHAR(45) DEFAULT NULL, model VARCHAR(45) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE institutionlocation');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, city VARCHAR(191) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, zip_code INT NOT NULL, street VARCHAR(191) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, number VARCHAR(5) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, bus VARCHAR(5) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE institutionlocation (id INT AUTO_INCREMENT NOT NULL, address_id INT DEFAULT NULL, institution_id INT DEFAULT NULL, institutionName VARCHAR(191) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, institutionNumber INT DEFAULT NULL, INDEX IDX_91DA8A6610405986 (institution_id), UNIQUE INDEX UNIQ_91DA8A66F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE institutionlocation ADD CONSTRAINT FK_91DA8A6610405986 FOREIGN KEY (institution_id) REFERENCES institution (id)');
        $this->addSql('ALTER TABLE institutionlocation ADD CONSTRAINT FK_91DA8A66F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('DROP TABLE ExtraDevice');
    }
}
