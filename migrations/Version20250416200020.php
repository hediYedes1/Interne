<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250416200020 extends AbstractMigration
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
            ALTER TABLE interview CHANGE idinterview idinterview INT AUTO_INCREMENT NOT NULL, CHANGE typeinterview typeinterview ENUM('ENLIGNE', 'ENPERSONNE') NOT NULL, CHANGE lienmeet lienmeet VARCHAR(255) DEFAULT NULL, CHANGE localisation localisation VARCHAR(255) DEFAULT NULL, CHANGE timeinterview timeinterview DATETIME NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offre DROP FOREIGN KEY FK_offre_projet
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX FK_offre_projet ON offre
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offre DROP idprojet
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet ADD idoffre INT NOT NULL, CHANGE titreprojet titreprojet VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet ADD CONSTRAINT FK_50159CA97983EA76 FOREIGN KEY (idoffre) REFERENCES offre (idoffre) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_50159CA97983EA76 ON projet (idoffre)
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
            ALTER TABLE interview CHANGE idinterview idinterview INT NOT NULL, CHANGE typeinterview typeinterview VARCHAR(255) NOT NULL, CHANGE lienmeet lienmeet VARCHAR(255) NOT NULL, CHANGE localisation localisation VARCHAR(255) NOT NULL, CHANGE timeinterview timeinterview VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offre ADD idprojet INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offre ADD CONSTRAINT FK_offre_projet FOREIGN KEY (idprojet) REFERENCES projet (idprojet)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX FK_offre_projet ON offre (idprojet)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet DROP FOREIGN KEY FK_50159CA97983EA76
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_50159CA97983EA76 ON projet
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet DROP idoffre, CHANGE titreprojet titreprojet LONGTEXT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE testtechnique CHANGE statuttesttechnique statuttesttechnique VARCHAR(255) NOT NULL, CHANGE datecreationtesttechnique datecreationtesttechnique DATETIME NOT NULL, CHANGE questions questions LONGTEXT NOT NULL
        SQL);
    }
}
