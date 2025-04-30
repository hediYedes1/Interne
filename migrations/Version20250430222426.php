<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250430222426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE face_recognition DROP FOREIGN KEY FK_2E7DE9A1A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE face_recognition
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_75EA56E0FB7336F0 ON messenger_messages
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_75EA56E0E3BD61CE ON messenger_messages
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_75EA56E016BA31DB ON messenger_messages
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messenger_messages CHANGE id id BIGINT NOT NULL, CHANGE created_at created_at VARCHAR(255) NOT NULL, CHANGE available_at available_at VARCHAR(255) NOT NULL, CHANGE delivered_at delivered_at VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur DROP two_factor_enabled, DROP two_factor_code, DROP two_factor_code_expires_at, DROP faceEmbedding, CHANGE is_banned is_banned TINYINT(1) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE face_recognition (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, face_descriptor JSON NOT NULL COMMENT '(DC2Type:json)', created_at DATETIME NOT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_2E7DE9A1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE face_recognition ADD CONSTRAINT FK_2E7DE9A1A76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (idutilisateur) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE messenger_messages CHANGE id id BIGINT AUTO_INCREMENT NOT NULL, CHANGE created_at created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', CHANGE available_at available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE utilisateur ADD two_factor_enabled TINYINT(1) NOT NULL, ADD two_factor_code VARCHAR(255) DEFAULT NULL, ADD two_factor_code_expires_at DATETIME DEFAULT NULL, ADD faceEmbedding TEXT DEFAULT NULL, CHANGE is_banned is_banned TINYINT(1) DEFAULT 0 NOT NULL
        SQL);
    }
}
