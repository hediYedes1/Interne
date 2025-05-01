<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250428211621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        //$this->addSql(<<<'SQL'
            //ALTER TABLE interview DROP FOREIGN KEY FK_INTERVIEW_OFFRE
        //SQL);
        //$this->addSql(<<<'SQL' //ALTER TABLE interview DROP FOREIGN KEY FK_CF1D3C347983EA76 SQL);
        //$this->addSql(<<<'SQL'ALTER TABLE interview DROP FOREIGN KEY FK_INTERVIEW_OFFRE SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE interview CHANGE typeinterview typeinterview ENUM('ENLIGNE', 'ENPERSONNE') NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_interview_offre ON interview
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_CF1D3C347983EA76 ON interview (idoffre)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE interview ADD CONSTRAINT FK_CF1D3C347983EA76 FOREIGN KEY (idoffre) REFERENCES offre (idoffre) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE interview ADD CONSTRAINT FK_INTERVIEW_OFFRE FOREIGN KEY (idoffre) REFERENCES offre (idoffre) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE testtechnique CHANGE statuttesttechnique statuttesttechnique ENUM('REFUSE', 'ACCEPTE', 'ENATTENTE') NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE interview DROP FOREIGN KEY FK_CF1D3C347983EA76
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE interview CHANGE typeinterview typeinterview VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_cf1d3c347983ea76 ON interview
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX FK_INTERVIEW_OFFRE ON interview (idoffre)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE interview ADD CONSTRAINT FK_CF1D3C347983EA76 FOREIGN KEY (idoffre) REFERENCES offre (idoffre) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE testtechnique CHANGE statuttesttechnique statuttesttechnique VARCHAR(255) NOT NULL
        SQL);
    }
}
