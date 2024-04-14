<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307142110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employe (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(30) NOT NULL, mdp VARCHAR(30) NOT NULL, nom VARCHAR(30) NOT NULL, prenom VARCHAR(30) NOT NULL, statut INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inscription (id INT AUTO_INCREMENT NOT NULL, lemploye_id INT DEFAULT NULL, la_formation_id INT DEFAULT NULL, statut VARCHAR(255) NOT NULL, INDEX IDX_5E90F6D6D3758FF7 (lemploye_id), INDEX IDX_5E90F6D6C6E58DBA (la_formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6D3758FF7 FOREIGN KEY (lemploye_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6C6E58DBA FOREIGN KEY (la_formation_id) REFERENCES formation (id)');
        $this->addSql('DROP TABLE employé');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employé (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, mdp VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, nom VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, prenom VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, statut INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D6D3758FF7');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D6C6E58DBA');
        $this->addSql('DROP TABLE employe');
        $this->addSql('DROP TABLE inscription');
    }
}
