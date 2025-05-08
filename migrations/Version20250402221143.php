<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402221143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE interview MODIFY idinterview INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON interview
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE interview ADD type_interview ENUM('ENLIGNE', 'ENPERSONNE') NOT NULL, DROP typeinterview, CHANGE idinterview id_interview INT AUTO_INCREMENT NOT NULL, CHANGE titreoffre titre_offre LONGTEXT NOT NULL, CHANGE dateinterview date_interview DATE NOT NULL, CHANGE lienmeet lien_meet VARCHAR(255) DEFAULT NULL, CHANGE timeinterview time_interview DATETIME NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE interview ADD PRIMARY KEY (id_interview)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE testtechnique CHANGE statuttesttechnique statuttesttechnique ENUM('REFUSE', 'ACCEPTE', 'ENATTENTE') NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE interview MODIFY id_interview INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON interview
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE interview ADD typeinterview VARCHAR(255) NOT NULL, DROP type_interview, CHANGE id_interview idinterview INT AUTO_INCREMENT NOT NULL, CHANGE titre_offre titreoffre LONGTEXT NOT NULL, CHANGE date_interview dateinterview DATE NOT NULL, CHANGE lien_meet lienmeet VARCHAR(255) DEFAULT NULL, CHANGE time_interview timeinterview DATETIME NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE interview ADD PRIMARY KEY (idinterview)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE testtechnique CHANGE statuttesttechnique statuttesttechnique VARCHAR(255) NOT NULL
        SQL);
    }
}
