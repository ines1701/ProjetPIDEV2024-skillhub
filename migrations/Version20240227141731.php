<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240227141731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation ADD favoris TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F45445200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('CREATE INDEX IDX_939F45445200282E ON ressource (formation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation DROP favoris');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F45445200282E');
        $this->addSql('DROP INDEX IDX_939F45445200282E ON ressource');
    }
}
