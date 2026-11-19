<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261119110037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vinyl ADD COLUMN image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE vinyl ADD COLUMN updated_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__vinyl AS SELECT id, artist, title, year, type, label, prod_country, prod_year, signed, description, library_id FROM vinyl');
        $this->addSql('DROP TABLE vinyl');
        $this->addSql('CREATE TABLE vinyl (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, artist VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, year INTEGER DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, prod_country VARCHAR(255) DEFAULT NULL, prod_year INTEGER DEFAULT NULL, signed BOOLEAN DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, library_id INTEGER NOT NULL, CONSTRAINT FK_E2E531DFE2541D7 FOREIGN KEY (library_id) REFERENCES library (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO vinyl (id, artist, title, year, type, label, prod_country, prod_year, signed, description, library_id) SELECT id, artist, title, year, type, label, prod_country, prod_year, signed, description, library_id FROM __temp__vinyl');
        $this->addSql('DROP TABLE __temp__vinyl');
        $this->addSql('CREATE INDEX IDX_E2E531DFE2541D7 ON vinyl (library_id)');
    }
}
