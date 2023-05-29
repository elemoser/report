<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230529101052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__adventure_room AS SELECT id, name, description, image, north, east, south, west, inpect FROM adventure_room');
        $this->addSql('DROP TABLE adventure_room');
        $this->addSql('CREATE TABLE adventure_room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, north VARCHAR(50) DEFAULT NULL, east VARCHAR(50) DEFAULT NULL, south VARCHAR(50) DEFAULT NULL, west VARCHAR(50) DEFAULT NULL, inspect CLOB NOT NULL)');
        $this->addSql('INSERT INTO adventure_room (id, name, description, image, north, east, south, west, inspect) SELECT id, name, description, image, north, east, south, west, inpect FROM __temp__adventure_room');
        $this->addSql('DROP TABLE __temp__adventure_room');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__adventure_room AS SELECT id, name, description, image, north, east, south, west, inspect FROM adventure_room');
        $this->addSql('DROP TABLE adventure_room');
        $this->addSql('CREATE TABLE adventure_room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, north VARCHAR(50) DEFAULT NULL, east VARCHAR(50) DEFAULT NULL, south VARCHAR(50) DEFAULT NULL, west VARCHAR(50) DEFAULT NULL, inpect CLOB NOT NULL)');
        $this->addSql('INSERT INTO adventure_room (id, name, description, image, north, east, south, west, inpect) SELECT id, name, description, image, north, east, south, west, inspect FROM __temp__adventure_room');
        $this->addSql('DROP TABLE __temp__adventure_room');
    }
}
