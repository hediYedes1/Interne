<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250408235054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE hebergement (idhebergement INT AUTO_INCREMENT NOT NULL, idpartenariat INT DEFAULT NULL, nomhebergement VARCHAR(255) NOT NULL, adressehebergement VARCHAR(255) NOT NULL, typehebergement VARCHAR(255) NOT NULL, descriptionhebergement LONGTEXT NOT NULL, nbrnuitehebergement INT NOT NULL, disponibilitehebergement TINYINT(1) NOT NULL, localisationhebergement VARCHAR(255) NOT NULL, prixhebergement DOUBLE PRECISION NOT NULL, INDEX IDX_4852DD9C7992AC29 (idpartenariat), PRIMARY KEY(idhebergement)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hebergement ADD CONSTRAINT FK_4852DD9C7992AC29 FOREIGN KEY (idpartenariat) REFERENCES partenariat (idpartenariat) ON DELETE CASCADE
        SQL);
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
            ALTER TABLE affectationhebergement DROP FOREIGN KEY FK_7EA380B68E93AD33
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hebergement DROP FOREIGN KEY FK_4852DD9C7992AC29
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE hebergement
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE interview CHANGE typeinterview typeinterview VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE testtechnique CHANGE statuttesttechnique statuttesttechnique VARCHAR(255) NOT NULL
        SQL);
    }
}
