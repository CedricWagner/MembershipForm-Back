<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230728144652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE member_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payment_method_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE member (id INT NOT NULL, payment_method_id INT DEFAULT NULL, num INT NOT NULL, firstname VARCHAR(127) NOT NULL, lastname VARCHAR(127) NOT NULL, email VARCHAR(255) DEFAULT NULL, amount NUMERIC(5, 2) NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, willing_to_volunteer BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_70E4FA785AA1164F ON member (payment_method_id)');
        $this->addSql('CREATE TABLE payment_method (id INT NOT NULL, name VARCHAR(45) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA785AA1164F FOREIGN KEY (payment_method_id) REFERENCES payment_method (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE member_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payment_method_id_seq CASCADE');
        $this->addSql('ALTER TABLE member DROP CONSTRAINT FK_70E4FA785AA1164F');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE payment_method');
    }
}
