<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230105032455 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $sql = <<<SQL
        ALTER TABLE collections
        ADD created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        ADD updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        SQL;
        $this->addSql($sql);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
