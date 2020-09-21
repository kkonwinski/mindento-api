<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200921184234 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE delegation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, delegation_country_id INTEGER DEFAULT NULL, employee_id INTEGER DEFAULT NULL, start_delegation DATETIME NOT NULL, finish_delegation DATETIME DEFAULT NULL, is_finish BOOLEAN NOT NULL)');
        $this->addSql('CREATE INDEX IDX_292F436DCFD89D9B ON delegation (delegation_country_id)');
        $this->addSql('CREATE INDEX IDX_292F436D8C03F15C ON delegation (employee_id)');
        $this->addSql('CREATE TABLE delegation_country (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, country VARCHAR(255) NOT NULL, amount_doe INTEGER NOT NULL, currency VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE employee (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE delegation');
        $this->addSql('DROP TABLE delegation_country');
        $this->addSql('DROP TABLE employee');
    }
}
