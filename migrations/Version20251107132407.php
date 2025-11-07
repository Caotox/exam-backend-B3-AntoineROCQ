<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20251107132407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création des tables pour l\'API F1 Infractions (MySQL)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE moteur (
            id INT AUTO_INCREMENT NOT NULL,
            marque VARCHAR(100) NOT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE ecurie (
            id INT AUTO_INCREMENT NOT NULL,
            moteur_id INT NOT NULL,
            nom VARCHAR(100) NOT NULL,
            UNIQUE INDEX UNIQ_B51A9B7E6C6E55B5 (nom),
            INDEX IDX_B51A9B7E6BF4B111 (moteur_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE pilote (
            id INT AUTO_INCREMENT NOT NULL,
            ecurie_id INT NOT NULL,
            prenom VARCHAR(100) NOT NULL,
            nom VARCHAR(100) NOT NULL,
            points_licence INT NOT NULL,
            date_debut_f1 DATE NOT NULL,
            statut VARCHAR(20) NOT NULL,
            etat VARCHAR(20) NOT NULL,
            INDEX IDX_6A3254DD57F92A74 (ecurie_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE infraction (
            id INT AUTO_INCREMENT NOT NULL,
            pilote_id INT DEFAULT NULL,
            ecurie_id INT DEFAULT NULL,
            description TEXT NOT NULL,
            penalite_points INT DEFAULT NULL,
            amende_euros NUMERIC(10, 2) DEFAULT NULL,
            nom_course VARCHAR(255) NOT NULL,
            date_infraction DATETIME NOT NULL,
            INDEX IDX_C1A458F5F510AAE9 (pilote_id),
            INDEX IDX_C1A458F557F92A74 (ecurie_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE user (
            id INT AUTO_INCREMENT NOT NULL,
            email VARCHAR(180) NOT NULL,
            roles JSON NOT NULL,
            password VARCHAR(255) NOT NULL,
            UNIQUE INDEX UNIQ_8D93D649E7927C74 (email),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE ecurie ADD CONSTRAINT FK_B51A9B7E6BF4B111 FOREIGN KEY (moteur_id) REFERENCES moteur (id)');
        $this->addSql('ALTER TABLE pilote ADD CONSTRAINT FK_6A3254DD57F92A74 FOREIGN KEY (ecurie_id) REFERENCES ecurie (id)');
        $this->addSql('ALTER TABLE infraction ADD CONSTRAINT FK_C1A458F5F510AAE9 FOREIGN KEY (pilote_id) REFERENCES pilote (id)');
        $this->addSql('ALTER TABLE infraction ADD CONSTRAINT FK_C1A458F557F92A74 FOREIGN KEY (ecurie_id) REFERENCES ecurie (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ecurie DROP FOREIGN KEY FK_B51A9B7E6BF4B111');
        $this->addSql('ALTER TABLE pilote DROP FOREIGN KEY FK_6A3254DD57F92A74');
        $this->addSql('ALTER TABLE infraction DROP FOREIGN KEY FK_C1A458F5F510AAE9');
        $this->addSql('ALTER TABLE infraction DROP FOREIGN KEY FK_C1A458F557F92A74');
        $this->addSql('DROP TABLE ecurie');
        $this->addSql('DROP TABLE pilote');
        $this->addSql('DROP TABLE infraction');
        $this->addSql('DROP TABLE moteur');
        $this->addSql('DROP TABLE user');
    }
}
