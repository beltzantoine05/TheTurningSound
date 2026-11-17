<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261013162721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bac (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, public BOOLEAN NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, library_id INTEGER NOT NULL, owner_id INTEGER NOT NULL, CONSTRAINT FK_1C4FAC58FE2541D7 FOREIGN KEY (library_id) REFERENCES library (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1C4FAC587E3C61F9 FOREIGN KEY (owner_id) REFERENCES member (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_1C4FAC58FE2541D7 ON bac (library_id)');
        $this->addSql('CREATE INDEX IDX_1C4FAC587E3C61F9 ON bac (owner_id)');
        $this->addSql('CREATE TABLE bac_vinyl (bac_id INTEGER NOT NULL, vinyl_id INTEGER NOT NULL, PRIMARY KEY (bac_id, vinyl_id), CONSTRAINT FK_75755F3E03F15C0 FOREIGN KEY (bac_id) REFERENCES bac (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_75755F33FFFF645 FOREIGN KEY (vinyl_id) REFERENCES vinyl (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_75755F3E03F15C0 ON bac_vinyl (bac_id)');
        $this->addSql('CREATE INDEX IDX_75755F33FFFF645 ON bac_vinyl (vinyl_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE bac');
        $this->addSql('DROP TABLE bac_vinyl');
    }
}
