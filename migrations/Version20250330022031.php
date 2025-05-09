<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250330022031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change le type de la colonne typeinterview pour VARCHAR et ENUM selon le besoin';
    }

    public function up(Schema $schema): void
    {
        // Modification du type de typeinterview en VARCHAR
        $this->addSql(<<<'SQL'
            ALTER TABLE interview CHANGE typeinterview typeinterview VARCHAR(20) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // Si tu veux revenir à ENUM, tu peux définir les valeurs possibles dans la base de données
        $this->addSql(<<<'SQL'
            ALTER TABLE interview CHANGE typeinterview typeinterview ENUM('ENLIGNE', 'ENPERSONNE') NOT NULL
        SQL);
    }
}
