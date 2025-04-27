<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250427162424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE loan ADD id INT AUTO_INCREMENT NOT NULL, ADD id_user INT NOT NULL, ADD id_media INT NOT NULL, DROP idUser, DROP idMedia, ADD PRIMARY KEY (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE medias CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE title title VARCHAR(60) NOT NULL, CHANGE author author VARCHAR(60) NOT NULL, CHANGE type type VARCHAR(60) NOT NULL, CHANGE description description LONGTEXT DEFAULT NULL, CHANGE image image VARCHAR(60) DEFAULT NULL, CHANGE status status TINYINT(1) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD firstname VARCHAR(60) NOT NULL, DROP fistname, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE lastname lastname VARCHAR(60) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE status status TINYINT(1) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE loan MODIFY id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON loan
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE loan ADD idUser INT NOT NULL COMMENT 'clé etrangère de l''identifiant utilisateur', ADD idMedia INT NOT NULL COMMENT 'clé étrangère de l''identifiant du média', DROP id, DROP id_user, DROP id_media
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE medias CHANGE id id INT AUTO_INCREMENT NOT NULL COMMENT 'identifiant d''un média', CHANGE title title VARCHAR(60) NOT NULL COMMENT 'titre d''un média', CHANGE author author VARCHAR(60) NOT NULL COMMENT 'auteur du média', CHANGE type type VARCHAR(60) NOT NULL COMMENT 'type du média', CHANGE description description TEXT NOT NULL COMMENT 'description du média', CHANGE image image VARCHAR(60) NOT NULL COMMENT 'chamin d''accès à l''image', CHANGE status status TINYINT(1) NOT NULL COMMENT 'est emprunté ? : oui ou non'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD fistname VARCHAR(60) NOT NULL COMMENT 'prenom de l''utilisateur', DROP firstname, CHANGE id id INT AUTO_INCREMENT NOT NULL COMMENT 'id de l''utilisateur', CHANGE lastname lastname VARCHAR(60) NOT NULL COMMENT 'nom de l''utilisateur', CHANGE email email VARCHAR(255) NOT NULL COMMENT 'email de l''utilisateur', CHANGE password password VARCHAR(255) NOT NULL COMMENT 'mot de passe de l''utilisateur', CHANGE status status TINYINT(1) NOT NULL COMMENT 'Admin ou non'
        SQL);
    }
}
