<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250508203457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire DROP FOREIGN KEY fk_publication
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire CHANGE id_publication id_publication INT DEFAULT NULL, CHANGE contenu contenu LONGTEXT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCB72EAA8E FOREIGN KEY (id_publication) REFERENCES publication (id_publication)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE publication CHANGE contenu contenu LONGTEXT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponse DROP FOREIGN KEY fk_commentaire
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponse CHANGE id_commentaire id_commentaire INT DEFAULT NULL, CHANGE contenu_reponse contenu_reponse LONGTEXT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC77FE2A54B FOREIGN KEY (id_commentaire) REFERENCES commentaire (id_commentaire)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCB72EAA8E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire CHANGE id_publication id_publication INT NOT NULL, CHANGE contenu contenu TEXT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaire ADD CONSTRAINT fk_publication FOREIGN KEY (id_publication) REFERENCES publication (id_publication) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE publication CHANGE contenu contenu TEXT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC77FE2A54B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponse CHANGE id_commentaire id_commentaire INT NOT NULL, CHANGE contenu_reponse contenu_reponse TEXT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reponse ADD CONSTRAINT fk_commentaire FOREIGN KEY (id_commentaire) REFERENCES commentaire (id_commentaire) ON DELETE CASCADE
        SQL);
    }
}
