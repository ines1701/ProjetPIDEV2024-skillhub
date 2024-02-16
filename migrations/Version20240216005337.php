<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240216005337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD latitude VARCHAR(255) NOT NULL, ADD longitude VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7C54C8C93 FOREIGN KEY (type_id) REFERENCES type_event (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7C54C8C93 ON event (type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7C54C8C93');
        $this->addSql('DROP INDEX IDX_3BAE0AA7C54C8C93 ON event');
        $this->addSql('ALTER TABLE event DROP latitude, DROP longitude');
    }
}
