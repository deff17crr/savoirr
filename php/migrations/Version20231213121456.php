<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231213121456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE country (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE TABLE member (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, country_id INTEGER DEFAULT NULL, political_group_id INTEGER DEFAULT NULL, national_political_group_id INTEGER DEFAULT NULL, mep_id INTEGER NOT NULL, full_name VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, CONSTRAINT FK_70E4FA78F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_70E4FA78F84E4482 FOREIGN KEY (political_group_id) REFERENCES political_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_70E4FA788F7F5F64 FOREIGN KEY (national_political_group_id) REFERENCES national_political_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_70E4FA78F92F3E70 ON member (country_id)');
        $this->addSql('CREATE INDEX IDX_70E4FA78F84E4482 ON member (political_group_id)');
        $this->addSql('CREATE INDEX IDX_70E4FA788F7F5F64 ON member (national_political_group_id)');
        $this->addSql('CREATE TABLE national_political_group (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE TABLE political_group (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE national_political_group');
        $this->addSql('DROP TABLE political_group');
    }
}
