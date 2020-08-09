<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200809083810 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mesurement CHANGE temperature temperature DOUBLE PRECISION DEFAULT NULL, CHANGE wind_speed wind_speed DOUBLE PRECISION DEFAULT NULL, CHANGE wind_direction wind_direction INT DEFAULT NULL, CHANGE pressure pressure DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mesurement CHANGE temperature temperature DOUBLE PRECISION NOT NULL, CHANGE wind_speed wind_speed DOUBLE PRECISION NOT NULL, CHANGE wind_direction wind_direction INT NOT NULL, CHANGE pressure pressure DOUBLE PRECISION NOT NULL');
    }
}
