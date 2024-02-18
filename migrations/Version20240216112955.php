<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240216112955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE jsp (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE ressource ADD formation_id INT NOT NULL, CHANGE file file VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F45445200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('CREATE INDEX IDX_939F45445200282E ON ressource (formation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE jsp');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F45445200282E');
        $this->addSql('DROP INDEX IDX_939F45445200282E ON ressource');
        $this->addSql('ALTER TABLE ressource DROP formation_id, CHANGE file file VARCHAR(255) DEFAULT NULL');
    }
}
