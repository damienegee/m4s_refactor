<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210705124729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE institution CHANGE synergy_id synergy_id BIGINT NOT NULL');
        // $this->addSql('ALTER TABLE institutionlocation DROP name');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Institution CHANGE synergy_id synergy_id INT NOT NULL');
        $this->addSql('ALTER TABLE InstitutionLocation ADD name VARCHAR(191) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`');
    }
}
