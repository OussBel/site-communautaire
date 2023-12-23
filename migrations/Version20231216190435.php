<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231216190435 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE illustrations ADD trick_id INT NOT NULL');
        $this->addSql('ALTER TABLE illustrations ADD CONSTRAINT FK_830A942DB281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id)');
        $this->addSql('CREATE INDEX IDX_830A942DB281BE2E ON illustrations (trick_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE illustrations DROP FOREIGN KEY FK_830A942DB281BE2E');
        $this->addSql('DROP INDEX IDX_830A942DB281BE2E ON illustrations');
        $this->addSql('ALTER TABLE illustrations DROP trick_id');
    }
}
