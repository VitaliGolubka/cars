<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220328141243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
//        $this->addSql('create domain text as text');
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vehicles (id SERIAL PRIMARY KEY, brand VARCHAR(255) DEFAULT NULL, model VARCHAR(255) DEFAULT NULL, version VARCHAR(255) DEFAULT NULL, year VARCHAR(255) DEFAULT NULL, energy VARCHAR(255) DEFAULT NULL, power INTEGER DEFAULT NULL, price INTEGER DEFAULT NULL, price_retail INTEGER DEFAULT NULL, price_monthly INTEGER DEFAULT NULL, pics text DEFAULT NULL
        )');
        $this->addSql('CREATE TABLE messenger_messages (id SERIAL PRIMARY KEY, body text NOT NULL, headers text NOT NULL, queue_name VARCHAR(255) NOT NULL, created_at TIMESTAMP NOT NULL, available_at TIMESTAMP NOT NULL, delivered_at TIMESTAMP DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE vehicles');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
