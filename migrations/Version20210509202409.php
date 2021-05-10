<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210509202409 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE juego ADD comprador_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE juego ADD CONSTRAINT FK_F0EC403D200A5E25 FOREIGN KEY (comprador_id) REFERENCES usuario (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F0EC403D200A5E25 ON juego (comprador_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE juego DROP FOREIGN KEY FK_F0EC403D200A5E25');
        $this->addSql('DROP INDEX UNIQ_F0EC403D200A5E25 ON juego');
        $this->addSql('ALTER TABLE juego DROP comprador_id');
    }
}
