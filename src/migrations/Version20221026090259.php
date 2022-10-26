<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221026090259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories_annonce (categories_id INT NOT NULL, annonce_id INT NOT NULL, INDEX IDX_DE5F4245A21214B7 (categories_id), INDEX IDX_DE5F42458805AB2F (annonce_id), PRIMARY KEY(categories_id, annonce_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categories_annonce ADD CONSTRAINT FK_DE5F4245A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_annonce ADD CONSTRAINT FK_DE5F42458805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories_annonce DROP FOREIGN KEY FK_DE5F4245A21214B7');
        $this->addSql('ALTER TABLE categories_annonce DROP FOREIGN KEY FK_DE5F42458805AB2F');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE categories_annonce');
    }
}
