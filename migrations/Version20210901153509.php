<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210901153509 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BC0317325CD4060B ON institution (synergy_id)');
        $this->addSql('ALTER TABLE movedevicelog CHANGE fromLocationId fromLocationId INT DEFAULT NULL, CHANGE fromLocationName fromLocationName VARCHAR(191) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_BC0317325CD4060B ON Institution');
        $this->addSql('ALTER TABLE MoveDeviceLog CHANGE fromLocationId fromLocationId INT NOT NULL, CHANGE fromLocationName fromLocationName VARCHAR(191) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
    }
}
