<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221007093628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce ADD slug VARCHAR(100) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F65593E5989D9B62 ON annonce (slug)');
        $this->addSql('ALTER TABLE question ADD slug VARCHAR(100) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B6F7494E989D9B62 ON question (slug)');
        $this->addSql('ALTER TABLE reponse ADD slug VARCHAR(100) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5FB6DEC7989D9B62 ON reponse (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_5FB6DEC7989D9B62 ON reponse');
        $this->addSql('ALTER TABLE reponse DROP slug');
        $this->addSql('DROP INDEX UNIQ_F65593E5989D9B62 ON annonce');
        $this->addSql('ALTER TABLE annonce DROP slug');
        $this->addSql('DROP INDEX UNIQ_B6F7494E989D9B62 ON question');
        $this->addSql('ALTER TABLE question DROP slug');
    }
}
