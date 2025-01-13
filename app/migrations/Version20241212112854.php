<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241212112854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Introduce basic Application';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE application (id BINARY(16) NOT NULL, status VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, estimated_expenses INT NOT NULL, iban VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE application');
    }
}
