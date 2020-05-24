<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200520090042 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE about (id INT AUTO_INCREMENT NOT NULL, table_name VARCHAR(100) NOT NULL, header VARCHAR(100) NOT NULL, image_path VARCHAR(255) NOT NULL, image_name VARCHAR(100) NOT NULL, text LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE album (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, event VARCHAR(255) NOT NULL, date DATE NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_39986E43A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE album_image (id INT AUTO_INCREMENT NOT NULL, image_id INT NOT NULL, album_id INT NOT NULL, INDEX IDX_B3854E793DA5256D (image_id), INDEX IDX_B3854E791137ABCF (album_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE album_tag (id INT AUTO_INCREMENT NOT NULL, album_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_6397379A1137ABCF (album_id), INDEX IDX_6397379ABAD26311 (tag_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, facebook_link VARCHAR(255) DEFAULT NULL, instagram_link VARCHAR(255) DEFAULT NULL, name VARCHAR(100) NOT NULL, phone_number INT DEFAULT NULL, email VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE download_log (id INT AUTO_INCREMENT NOT NULL, image_id INT NOT NULL, user_id INT NOT NULL, downloaded_at DATETIME NOT NULL, INDEX IDX_A5291D983DA5256D (image_id), INDEX IDX_A5291D98A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, alt VARCHAR(255) NOT NULL, size INT NOT NULL, uploaded_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `like` (id INT AUTO_INCREMENT NOT NULL, image_id INT NOT NULL, user_id INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_AC6340B33DA5256D (image_id), INDEX IDX_AC6340B3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, ordering INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, receiver_id INT NOT NULL, text LONGTEXT NOT NULL, phone_number INT NOT NULL, sent_at DATETIME NOT NULL, INDEX IDX_B6BD307FF624B39D (sender_id), INDEX IDX_B6BD307FCD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json_document)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(50) NOT NULL, lastname VARCHAR(100) NOT NULL, cosplay_name VARCHAR(100) DEFAULT NULL, plain_password VARCHAR(100) DEFAULT NULL, regkey VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE album ADD CONSTRAINT FK_39986E43A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE album_image ADD CONSTRAINT FK_B3854E793DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE album_image ADD CONSTRAINT FK_B3854E791137ABCF FOREIGN KEY (album_id) REFERENCES album (id)');
        $this->addSql('ALTER TABLE album_tag ADD CONSTRAINT FK_6397379A1137ABCF FOREIGN KEY (album_id) REFERENCES album (id)');
        $this->addSql('ALTER TABLE album_tag ADD CONSTRAINT FK_6397379ABAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)');
        $this->addSql('ALTER TABLE download_log ADD CONSTRAINT FK_A5291D983DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE download_log ADD CONSTRAINT FK_A5291D98A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B33DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE album_image DROP FOREIGN KEY FK_B3854E791137ABCF');
        $this->addSql('ALTER TABLE album_tag DROP FOREIGN KEY FK_6397379A1137ABCF');
        $this->addSql('ALTER TABLE album_image DROP FOREIGN KEY FK_B3854E793DA5256D');
        $this->addSql('ALTER TABLE download_log DROP FOREIGN KEY FK_A5291D983DA5256D');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B33DA5256D');
        $this->addSql('ALTER TABLE album_tag DROP FOREIGN KEY FK_6397379ABAD26311');
        $this->addSql('ALTER TABLE album DROP FOREIGN KEY FK_39986E43A76ED395');
        $this->addSql('ALTER TABLE download_log DROP FOREIGN KEY FK_A5291D98A76ED395');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3A76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCD53EDB6');
        $this->addSql('DROP TABLE about');
        $this->addSql('DROP TABLE album');
        $this->addSql('DROP TABLE album_image');
        $this->addSql('DROP TABLE album_tag');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE download_log');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE `like`');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE user');
    }
}
