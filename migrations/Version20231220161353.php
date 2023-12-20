<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231220161353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, brand VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, outlet_type VARCHAR(50) NOT NULL, INDEX IDX_773DE69DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opened (id INT AUTO_INCREMENT NOT NULL, day VARCHAR(9) NOT NULL, time_slot_start TIME NOT NULL, time_slot_end TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opened_terminal (opened_id INT NOT NULL, terminal_id INT NOT NULL, INDEX IDX_47883A785E654268 (opened_id), INDEX IDX_47883A78E77B6CE8 (terminal_id), PRIMARY KEY(opened_id, terminal_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE terminal (id INT AUTO_INCREMENT NOT NULL, town_id INT DEFAULT NULL, latitude_y DOUBLE PRECISION NOT NULL, longitude_x DOUBLE PRECISION NOT NULL, address VARCHAR(255) NOT NULL, outlet_type VARCHAR(255) NOT NULL, number_outlet INT NOT NULL, max_power INT NOT NULL, INDEX IDX_8F7B154175E23604 (town_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE town (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, postal_code INT NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, town_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, birthday DATE NOT NULL, gender VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D64975E23604 (town_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE opened_terminal ADD CONSTRAINT FK_47883A785E654268 FOREIGN KEY (opened_id) REFERENCES opened (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE opened_terminal ADD CONSTRAINT FK_47883A78E77B6CE8 FOREIGN KEY (terminal_id) REFERENCES terminal (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE terminal ADD CONSTRAINT FK_8F7B154175E23604 FOREIGN KEY (town_id) REFERENCES town (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64975E23604 FOREIGN KEY (town_id) REFERENCES town (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DA76ED395');
        $this->addSql('ALTER TABLE opened_terminal DROP FOREIGN KEY FK_47883A785E654268');
        $this->addSql('ALTER TABLE opened_terminal DROP FOREIGN KEY FK_47883A78E77B6CE8');
        $this->addSql('ALTER TABLE terminal DROP FOREIGN KEY FK_8F7B154175E23604');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64975E23604');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE opened');
        $this->addSql('DROP TABLE opened_terminal');
        $this->addSql('DROP TABLE terminal');
        $this->addSql('DROP TABLE town');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
