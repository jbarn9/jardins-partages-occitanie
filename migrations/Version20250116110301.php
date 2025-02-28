<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250116110301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resources ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE resources ADD CONSTRAINT FK_EF66EBAEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_EF66EBAEA76ED395 ON resources (user_id)');
        $this->addSql('ALTER TABLE user DROP resources_id');
        $this->addSql('ALTER TABLE videos ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE videos ADD CONSTRAINT FK_29AA6432A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_29AA6432A76ED395 ON videos (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resources DROP FOREIGN KEY FK_EF66EBAEA76ED395');
        $this->addSql('DROP INDEX IDX_EF66EBAEA76ED395 ON resources');
        $this->addSql('ALTER TABLE resources DROP user_id');
        $this->addSql('ALTER TABLE user ADD resources_id INT NOT NULL');
        $this->addSql('ALTER TABLE videos DROP FOREIGN KEY FK_29AA6432A76ED395');
        $this->addSql('DROP INDEX IDX_29AA6432A76ED395 ON videos');
        $this->addSql('ALTER TABLE videos DROP user_id');
    }
}
