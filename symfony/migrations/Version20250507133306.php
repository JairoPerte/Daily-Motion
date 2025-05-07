<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250507133306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Crea la tabla de Categoria';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE category (
                id UUID PRIMARY KEY NOT NULL,
                user_id UUID NOT NULL,
                icon INTEGER NOT NULL,
                name VARCHAR(100) NOT NULL
            );
        SQL);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable("category");
    }
}
