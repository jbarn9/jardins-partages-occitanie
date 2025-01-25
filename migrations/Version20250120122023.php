<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250120122023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE links ADD association_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE links ADD CONSTRAINT FK_D182A118EFB9C8A5 FOREIGN KEY (association_id) REFERENCES association (id)');
        $this->addSql('CREATE INDEX IDX_D182A118EFB9C8A5 ON links (association_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE links DROP FOREIGN KEY FK_D182A118EFB9C8A5');
        $this->addSql('DROP INDEX IDX_D182A118EFB9C8A5 ON links');
        $this->addSql('ALTER TABLE links DROP association_id');
    }
}
