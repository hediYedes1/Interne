<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250401113125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE interview CHANGE typeinterview typeinterview ENUM('ENLIGNE', 'ENPERSONNE') NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE testtechnique CHANGE statuttesttechnique statuttesttechnique ENUM('REFUSE', 'ACCEPTE', 'ENATTENTE') NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE interview CHANGE typeinterview typeinterview VARCHAR(20) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE testtechnique CHANGE statuttesttechnique statuttesttechnique VARCHAR(255) NOT NULL
        SQL);
    }
}
