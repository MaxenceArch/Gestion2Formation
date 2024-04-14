<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231207153030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inscription (id INT AUTO_INCREMENT NOT NULL, employé_id INT DEFAULT NULL, formation_id INT DEFAULT NULL, statut VARCHAR(30) NOT NULL, login VARCHAR(30) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, INDEX IDX_5E90F6D64FFFCAD4 (employé_id), INDEX IDX_5E90F6D65200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D64FFFCAD4 FOREIGN KEY (employé_id) REFERENCES employé (id)');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D65200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D64FFFCAD4');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D65200282E');
        $this->addSql('DROP TABLE inscription');
    }
}
