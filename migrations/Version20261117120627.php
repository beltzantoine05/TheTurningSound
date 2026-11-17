<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261117120627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__bac AS SELECT id, public, name, description, owner_id FROM bac');
        $this->addSql('DROP TABLE bac');
        $this->addSql('CREATE TABLE bac (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, public BOOLEAN NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, owner_id INTEGER NOT NULL, CONSTRAINT FK_1C4FAC587E3C61F9 FOREIGN KEY (owner_id) REFERENCES member (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO bac (id, public, name, description, owner_id) SELECT id, public, name, description, owner_id FROM __temp__bac');
        $this->addSql('DROP TABLE __temp__bac');
        $this->addSql('CREATE INDEX IDX_1C4FAC587E3C61F9 ON bac (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__bac AS SELECT id, public, name, description, owner_id FROM bac');
        $this->addSql('DROP TABLE bac');
        $this->addSql('CREATE TABLE bac (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, public BOOLEAN NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, owner_id INTEGER NOT NULL, library_id INTEGER NOT NULL, CONSTRAINT FK_1C4FAC587E3C61F9 FOREIGN KEY (owner_id) REFERENCES member (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1C4FAC58FE2541D7 FOREIGN KEY (library_id) REFERENCES library (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO bac (id, public, name, description, owner_id) SELECT id, public, name, description, owner_id FROM __temp__bac');
        $this->addSql('DROP TABLE __temp__bac');
        $this->addSql('CREATE INDEX IDX_1C4FAC587E3C61F9 ON bac (owner_id)');
        $this->addSql('CREATE INDEX IDX_1C4FAC58FE2541D7 ON bac (library_id)');
    }
}
