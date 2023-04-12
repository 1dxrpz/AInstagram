<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230411205323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE post_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE post_likes_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_posts_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE post (id INT NOT NULL, prompt VARCHAR(2048) NOT NULL, description VARCHAR(2048) DEFAULT NULL, image_url VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE post_likes (id INT NOT NULL, likes INT NOT NULL, dislikes INT NOT NULL, post_id VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, name VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, description VARCHAR(2048) NOT NULL, email VARCHAR(64) NOT NULL, avatarid VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6495E237E06 ON "user" (name)');
        $this->addSql('CREATE TABLE user_posts (id INT NOT NULL, post_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE post_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE post_likes_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE user_posts_id_seq CASCADE');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_likes');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_posts');
    }
}
