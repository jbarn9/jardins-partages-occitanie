<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250120161845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subject_email (id INT AUTO_INCREMENT NOT NULL, association_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, INDEX IDX_F601BDACEFB9C8A5 (association_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subject_email ADD CONSTRAINT FK_F601BDACEFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subject_email DROP FOREIGN KEY FK_F601BDACEFB9C8A5');
        $this->addSql('DROP TABLE subject_email');
    }
}
