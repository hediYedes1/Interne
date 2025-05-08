<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250329232408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE brancheentreprise CHANGE idbranche idbranche INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentairepublication CHANGE idcommentaire idcommentaire INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE departmententreprise CHANGE iddepartement iddepartement INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE entreprise CHANGE identreprise identreprise INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hebergement CHANGE idhebergement idhebergement INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE interview CHANGE idinterview idinterview INT NOT NULL, CHANGE lienmeet lienmeet VARCHAR(255) NOT NULL, CHANGE localisation localisation VARCHAR(255) NOT NULL, CHANGE timeinterview timeinterview VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messenger_messages CHANGE id id BIGINT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messengermessages CHANGE id id BIGINT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offre CHANGE idoffre idoffre INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE partenariat CHANGE idpartenariat idpartenariat INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet CHANGE idprojet idprojet INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE publication CHANGE idpublication idpublication INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponsecommentairepublication CHANGE idreponsecommentaire idreponsecommentaire INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE testtechnique CHANGE idtesttechnique idtesttechnique INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur CHANGE idutilisateur idutilisateur INT NOT NULL, CHANGE resettoken resettoken VARCHAR(255) NOT NULL, CHANGE profilepictureurl profilepictureurl VARCHAR(255) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE brancheentreprise CHANGE idbranche idbranche INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentairepublication CHANGE idcommentaire idcommentaire INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE departmententreprise CHANGE iddepartement iddepartement INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE entreprise CHANGE identreprise identreprise INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hebergement CHANGE idhebergement idhebergement INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE interview CHANGE idinterview idinterview INT AUTO_INCREMENT NOT NULL, CHANGE lienmeet lienmeet VARCHAR(255) DEFAULT NULL, CHANGE localisation localisation VARCHAR(255) DEFAULT NULL, CHANGE timeinterview timeinterview DATETIME NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messengermessages CHANGE id id BIGINT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messenger_messages CHANGE id id BIGINT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offre CHANGE idoffre idoffre INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE partenariat CHANGE idpartenariat idpartenariat INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet CHANGE idprojet idprojet INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE publication CHANGE idpublication idpublication INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponsecommentairepublication CHANGE idreponsecommentaire idreponsecommentaire INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE testtechnique CHANGE idtesttechnique idtesttechnique INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur CHANGE idutilisateur idutilisateur INT AUTO_INCREMENT NOT NULL, CHANGE resettoken resettoken VARCHAR(255) DEFAULT NULL, CHANGE profilepictureurl profilepictureurl VARCHAR(255) DEFAULT NULL
        SQL);
    }
}
