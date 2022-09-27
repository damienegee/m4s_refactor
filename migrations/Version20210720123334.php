<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210720123334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE institution_user (institution_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_9B90060210405986 (institution_id), INDEX IDX_9B900602A76ED395 (user_id), PRIMARY KEY(institution_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE institution_user ADD CONSTRAINT FK_9B90060210405986 FOREIGN KEY (institution_id) REFERENCES Institution (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE institution_user ADD CONSTRAINT FK_9B900602A76ED395 FOREIGN KEY (user_id) REFERENCES User (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE institution DROP FOREIGN KEY FK_3A9F98E5A76ED395');
        $this->addSql('DROP INDEX IDX_BC031732A76ED395 ON institution');
        $this->addSql('ALTER TABLE institution DROP user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE institution_user');
        $this->addSql('ALTER TABLE Institution ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Institution ADD CONSTRAINT FK_3A9F98E5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_BC031732A76ED395 ON Institution (user_id)');
    }
}
