<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210531065411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F8110405986');
        $this->addSql('DROP INDEX idx_d4e6f8110405986 ON address');
        $this->addSql('CREATE INDEX IDX_C2F3561D10405986 ON address (institution_id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F8110405986 FOREIGN KEY (institution_id) REFERENCES institution (id)');
        $this->addSql('ALTER TABLE institution DROP FOREIGN KEY FK_3A9F98E5A76ED395');
        $this->addSql('DROP INDEX idx_3a9f98e5a76ed395 ON institution');
        $this->addSql('CREATE INDEX IDX_BC031732A76ED395 ON institution (user_id)');
        $this->addSql('ALTER TABLE institution ADD CONSTRAINT FK_3A9F98E5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP INDEX uniq_8d93d649e7927c74 ON user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2DA17977E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Address DROP FOREIGN KEY FK_C2F3561D10405986');
        $this->addSql('DROP INDEX idx_c2f3561d10405986 ON Address');
        $this->addSql('CREATE INDEX IDX_D4E6F8110405986 ON Address (institution_id)');
        $this->addSql('ALTER TABLE Address ADD CONSTRAINT FK_C2F3561D10405986 FOREIGN KEY (institution_id) REFERENCES Institution (id)');
        $this->addSql('ALTER TABLE Institution DROP FOREIGN KEY FK_BC031732A76ED395');
        $this->addSql('DROP INDEX idx_bc031732a76ed395 ON Institution');
        $this->addSql('CREATE INDEX IDX_3A9F98E5A76ED395 ON Institution (user_id)');
        $this->addSql('ALTER TABLE Institution ADD CONSTRAINT FK_BC031732A76ED395 FOREIGN KEY (user_id) REFERENCES User (id)');
        $this->addSql('DROP INDEX uniq_2da17977e7927c74 ON User');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON User (email)');
    }
}
