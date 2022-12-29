<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221229031325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $sql = <<<SQL
        CREATE TABLE collections (
            id INT UNSIGNED AUTO_INCREMENT NOT NULL,
            name VARCHAR (100) NOT NULL,
            api_key VARCHAR (255) NOT NULL,
            PRIMARY KEY(id)
        )
        SQL;
        $this->addSql($sql);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE collections');
    }
}