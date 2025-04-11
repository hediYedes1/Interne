<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250408234334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE affectationhebergement (id INT AUTO_INCREMENT NOT NULL, idhebergement INT DEFAULT NULL, idutilisateur INT DEFAULT NULL, datedebut DATE NOT NULL, datefin DATE NOT NULL, INDEX IDX_7EA380B68E93AD33 (idhebergement), INDEX IDX_7EA380B6DBDD131C (idutilisateur), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE affectationinterview (idinterview INT NOT NULL, idutilisateur INT NOT NULL, dateaffectationinterview DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_BAC1D867B10695C1 (idinterview), INDEX IDX_BAC1D867DBDD131C (idutilisateur), PRIMARY KEY(idinterview, idutilisateur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE brancheentreprise (idbranche INT AUTO_INCREMENT NOT NULL, identreprise INT DEFAULT NULL, idutilisateur INT DEFAULT NULL, localisationbranche LONGTEXT NOT NULL, emailbranche LONGTEXT NOT NULL, contactbranche VARCHAR(15) NOT NULL, nombreemploye INT NOT NULL, responsablebranche LONGTEXT NOT NULL, INDEX IDX_D804E0A6C0B0E75A (identreprise), INDEX IDX_D804E0A6DBDD131C (idutilisateur), PRIMARY KEY(idbranche)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE commentairepublication (idcommentaire INT NOT NULL, idpublication INT DEFAULT NULL, idutilisateur INT DEFAULT NULL, idreponse INT DEFAULT NULL, contenu LONGTEXT NOT NULL, datecommentaire DATETIME NOT NULL, INDEX IDX_7445A58169FD17D6 (idpublication), INDEX IDX_7445A581DBDD131C (idutilisateur), INDEX IDX_7445A58180B28CA9 (idreponse), PRIMARY KEY(idcommentaire)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE departmententreprise (iddepartement INT NOT NULL, identreprise INT DEFAULT NULL, nomdepartement LONGTEXT NOT NULL, descriptiondepartement LONGTEXT NOT NULL, responsabledepartement LONGTEXT NOT NULL, nbremployedepartement INT NOT NULL, INDEX IDX_9B072CB1C0B0E75A (identreprise), PRIMARY KEY(iddepartement)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE entreprise (identreprise INT NOT NULL, nomentreprise LONGTEXT NOT NULL, descriptionentreprise LONGTEXT NOT NULL, logoentreprise VARCHAR(255) NOT NULL, urlentreprise LONGTEXT NOT NULL, secteurentreprise LONGTEXT NOT NULL, PRIMARY KEY(identreprise)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE hebergement (idhebergement INT AUTO_INCREMENT NOT NULL, idpartenariat INT DEFAULT NULL, nomhebergement VARCHAR(255) NOT NULL, adressehebergement VARCHAR(255) NOT NULL, typehebergement VARCHAR(255) NOT NULL, descriptionhebergement LONGTEXT NOT NULL, nbrnuitehebergement INT NOT NULL, disponibilitehebergement TINYINT(1) NOT NULL, localisationhebergement VARCHAR(255) NOT NULL, prixhebergement DOUBLE PRECISION NOT NULL, INDEX IDX_4852DD9C7992AC29 (idpartenariat), PRIMARY KEY(idhebergement)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE interview (idinterview INT AUTO_INCREMENT NOT NULL, idoffre INT DEFAULT NULL, titreoffre LONGTEXT NOT NULL, dateinterview DATE NOT NULL, typeinterview ENUM('ENLIGNE', 'ENPERSONNE') NOT NULL, lienmeet VARCHAR(255) DEFAULT NULL, localisation VARCHAR(255) DEFAULT NULL, timeinterview DATETIME NOT NULL, INDEX IDX_CF1D3C347983EA76 (idoffre), PRIMARY KEY(idinterview)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at VARCHAR(255) NOT NULL, available_at VARCHAR(255) NOT NULL, delivered_at VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messengermessages (id BIGINT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queuename VARCHAR(190) NOT NULL, createdat DATETIME NOT NULL, availableat DATETIME NOT NULL, deliveredat DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE offre (idoffre INT AUTO_INCREMENT NOT NULL, idutilisateur INT DEFAULT NULL, identreprise INT DEFAULT NULL, titreoffre LONGTEXT NOT NULL, descriptionoffre LONGTEXT NOT NULL, salaireoffre DOUBLE PRECISION NOT NULL, localisationoffre LONGTEXT NOT NULL, emailrh LONGTEXT NOT NULL, typecontrat LONGTEXT NOT NULL, datelimite DATE NOT NULL, INDEX IDX_AF86866FDBDD131C (idutilisateur), INDEX IDX_AF86866FC0B0E75A (identreprise), PRIMARY KEY(idoffre)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE partenariat (idpartenariat INT AUTO_INCREMENT NOT NULL, idbranche INT DEFAULT NULL, nompartenariat VARCHAR(255) NOT NULL, adressepartenariat VARCHAR(255) NOT NULL, numtelpartenariat VARCHAR(15) NOT NULL, INDEX IDX_BF53DC86BD6DA3DA (idbranche), PRIMARY KEY(idpartenariat)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE projet (idprojet INT NOT NULL, idoffre INT DEFAULT NULL, titreprojet LONGTEXT NOT NULL, descriptionprojet LONGTEXT NOT NULL, datedebut DATE NOT NULL, datefin DATE NOT NULL, INDEX IDX_50159CA97983EA76 (idoffre), PRIMARY KEY(idprojet)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE publication (idpublication INT NOT NULL, idutilisateur INT DEFAULT NULL, contenu LONGTEXT NOT NULL, datepublication DATETIME NOT NULL, INDEX IDX_AF3C6779DBDD131C (idutilisateur), PRIMARY KEY(idpublication)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE repondre (idreponsecommentaire INT NOT NULL, idcommentaire INT NOT NULL, idutilisateur INT NOT NULL, INDEX IDX_F66FAAF42450683F (idreponsecommentaire), INDEX IDX_F66FAAF4A1311813 (idcommentaire), INDEX IDX_F66FAAF4DBDD131C (idutilisateur), PRIMARY KEY(idreponsecommentaire, idcommentaire, idutilisateur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reponsecommentairepublication (idreponsecommentaire INT NOT NULL, contenureponsecommentairepublication LONGTEXT NOT NULL, datereponsecommentairepublication DATE NOT NULL, PRIMARY KEY(idreponsecommentaire)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE testtechnique (idtesttechnique INT AUTO_INCREMENT NOT NULL, idinterview INT DEFAULT NULL, titretesttechnique VARCHAR(255) NOT NULL, descriptiontesttechnique VARCHAR(255) NOT NULL, dureetesttechnique INT NOT NULL, statuttesttechnique ENUM('REFUSE', 'ACCEPTE', 'ENATTENTE') NOT NULL, datecreationtesttechnique DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, questions LONGTEXT DEFAULT NULL, INDEX IDX_6C98D677B10695C1 (idinterview), PRIMARY KEY(idtesttechnique)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE utilisateur (idutilisateur INT NOT NULL, nomutilisateur VARCHAR(100) NOT NULL, prenomutilisateur VARCHAR(100) NOT NULL, ageutilisateur INT NOT NULL, emailutilisateur VARCHAR(255) NOT NULL, motdepasseutilisateur VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, resettoken VARCHAR(255) NOT NULL, profilepictureurl VARCHAR(255) NOT NULL, PRIMARY KEY(idutilisateur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE affectationhebergement ADD CONSTRAINT FK_7EA380B68E93AD33 FOREIGN KEY (idhebergement) REFERENCES hebergement (idhebergement) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE affectationhebergement ADD CONSTRAINT FK_7EA380B6DBDD131C FOREIGN KEY (idutilisateur) REFERENCES utilisateur (idutilisateur) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE affectationinterview ADD CONSTRAINT FK_BAC1D867B10695C1 FOREIGN KEY (idinterview) REFERENCES interview (idinterview) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE affectationinterview ADD CONSTRAINT FK_BAC1D867DBDD131C FOREIGN KEY (idutilisateur) REFERENCES utilisateur (idutilisateur) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE brancheentreprise ADD CONSTRAINT FK_D804E0A6C0B0E75A FOREIGN KEY (identreprise) REFERENCES entreprise (identreprise) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE brancheentreprise ADD CONSTRAINT FK_D804E0A6DBDD131C FOREIGN KEY (idutilisateur) REFERENCES utilisateur (idutilisateur) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentairepublication ADD CONSTRAINT FK_7445A58169FD17D6 FOREIGN KEY (idpublication) REFERENCES publication (idpublication) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentairepublication ADD CONSTRAINT FK_7445A581DBDD131C FOREIGN KEY (idutilisateur) REFERENCES utilisateur (idutilisateur) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentairepublication ADD CONSTRAINT FK_7445A58180B28CA9 FOREIGN KEY (idreponse) REFERENCES commentairepublication (idcommentaire) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE departmententreprise ADD CONSTRAINT FK_9B072CB1C0B0E75A FOREIGN KEY (identreprise) REFERENCES entreprise (identreprise) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hebergement ADD CONSTRAINT FK_4852DD9C7992AC29 FOREIGN KEY (idpartenariat) REFERENCES partenariat (idpartenariat) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE interview ADD CONSTRAINT FK_CF1D3C347983EA76 FOREIGN KEY (idoffre) REFERENCES offre (idoffre) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offre ADD CONSTRAINT FK_AF86866FDBDD131C FOREIGN KEY (idutilisateur) REFERENCES utilisateur (idutilisateur) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offre ADD CONSTRAINT FK_AF86866FC0B0E75A FOREIGN KEY (identreprise) REFERENCES entreprise (identreprise) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE partenariat ADD CONSTRAINT FK_BF53DC86BD6DA3DA FOREIGN KEY (idbranche) REFERENCES brancheentreprise (idbranche) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet ADD CONSTRAINT FK_50159CA97983EA76 FOREIGN KEY (idoffre) REFERENCES offre (idoffre) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779DBDD131C FOREIGN KEY (idutilisateur) REFERENCES utilisateur (idutilisateur) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE repondre ADD CONSTRAINT FK_F66FAAF42450683F FOREIGN KEY (idreponsecommentaire) REFERENCES reponsecommentairepublication (idreponsecommentaire) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE repondre ADD CONSTRAINT FK_F66FAAF4A1311813 FOREIGN KEY (idcommentaire) REFERENCES commentairepublication (idcommentaire) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE repondre ADD CONSTRAINT FK_F66FAAF4DBDD131C FOREIGN KEY (idutilisateur) REFERENCES utilisateur (idutilisateur) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE testtechnique ADD CONSTRAINT FK_6C98D677B10695C1 FOREIGN KEY (idinterview) REFERENCES interview (idinterview) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE affectationhebergement DROP FOREIGN KEY FK_7EA380B68E93AD33
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE affectationhebergement DROP FOREIGN KEY FK_7EA380B6DBDD131C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE affectationinterview DROP FOREIGN KEY FK_BAC1D867B10695C1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE affectationinterview DROP FOREIGN KEY FK_BAC1D867DBDD131C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE brancheentreprise DROP FOREIGN KEY FK_D804E0A6C0B0E75A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE brancheentreprise DROP FOREIGN KEY FK_D804E0A6DBDD131C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentairepublication DROP FOREIGN KEY FK_7445A58169FD17D6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentairepublication DROP FOREIGN KEY FK_7445A581DBDD131C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentairepublication DROP FOREIGN KEY FK_7445A58180B28CA9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE departmententreprise DROP FOREIGN KEY FK_9B072CB1C0B0E75A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hebergement DROP FOREIGN KEY FK_4852DD9C7992AC29
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE interview DROP FOREIGN KEY FK_CF1D3C347983EA76
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FDBDD131C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FC0B0E75A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE partenariat DROP FOREIGN KEY FK_BF53DC86BD6DA3DA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet DROP FOREIGN KEY FK_50159CA97983EA76
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779DBDD131C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE repondre DROP FOREIGN KEY FK_F66FAAF42450683F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE repondre DROP FOREIGN KEY FK_F66FAAF4A1311813
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE repondre DROP FOREIGN KEY FK_F66FAAF4DBDD131C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE testtechnique DROP FOREIGN KEY FK_6C98D677B10695C1
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE affectationhebergement
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE affectationinterview
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE brancheentreprise
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE commentairepublication
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE departmententreprise
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE entreprise
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE hebergement
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE interview
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messengermessages
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE offre
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE partenariat
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE projet
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE publication
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE repondre
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reponsecommentairepublication
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE testtechnique
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE utilisateur
        SQL);
    }
}
