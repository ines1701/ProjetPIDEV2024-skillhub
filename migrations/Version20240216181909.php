<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240216181909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE condidature ADD project_id INT NOT NULL');
        $this->addSql('ALTER TABLE condidature ADD CONSTRAINT FK_FDF2E30B166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_FDF2E30B166D1F9C ON condidature (project_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE condidature DROP FOREIGN KEY FK_FDF2E30B166D1F9C');
        $this->addSql('DROP INDEX IDX_FDF2E30B166D1F9C ON condidature');
        $this->addSql('ALTER TABLE condidature DROP project_id');
    }
}
