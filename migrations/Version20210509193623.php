<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210509193623 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE juego ADD CONSTRAINT FK_F0EC403D53C8D32C FOREIGN KEY (propietario_id) REFERENCES usuario (id)');
        $this->addSql('CREATE INDEX IDX_F0EC403D53C8D32C ON juego (propietario_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE juego DROP FOREIGN KEY FK_F0EC403D53C8D32C');
        $this->addSql('DROP INDEX IDX_F0EC403D53C8D32C ON juego');
    }
}
