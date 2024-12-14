<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241212114621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Set nullable';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE application CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE estimated_expenses estimated_expenses INT DEFAULT NULL, CHANGE iban iban VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE application CHANGE name name VARCHAR(255) NOT NULL, CHANGE estimated_expenses estimated_expenses INT NOT NULL, CHANGE iban iban VARCHAR(255) NOT NULL');
    }
}
