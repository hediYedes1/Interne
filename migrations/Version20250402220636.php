<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402220636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE affectationinterview CHANGE dateaffectationinterview dateaffectationinterview DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE interview CHANGE typeinterview typeinterview ENUM('ENLIGNE', 'ENPERSONNE') NOT NULL, CHANGE lienmeet lienmeet VARCHAR(255) DEFAULT NULL, CHANGE localisation localisation VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE testtechnique CHANGE statuttesttechnique statuttesttechnique ENUM('REFUSE', 'ACCEPTE', 'ENATTENTE') NOT NULL, CHANGE datecreationtesttechnique datecreationtesttechnique DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE questions questions LONGTEXT DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE affectationinterview CHANGE dateaffectationinterview dateaffectationinterview DATETIME NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE interview CHANGE typeinterview typeinterview VARCHAR(255) NOT NULL, CHANGE lienmeet lienmeet VARCHAR(255) NOT NULL, CHANGE localisation localisation VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE testtechnique CHANGE statuttesttechnique statuttesttechnique VARCHAR(255) NOT NULL, CHANGE datecreationtesttechnique datecreationtesttechnique DATETIME NOT NULL, CHANGE questions questions LONGTEXT NOT NULL
        SQL);
    }
}
