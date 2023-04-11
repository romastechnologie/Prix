<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230411120843 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categ_client (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(20) NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, cate_client_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, telephone1 VARCHAR(255) DEFAULT NULL, telephone2 VARCHAR(255) DEFAULT NULL, raison_sociale VARCHAR(255) DEFAULT NULL, ifu VARCHAR(13) DEFAULT NULL, rccm VARCHAR(255) DEFAULT NULL, sigle VARCHAR(255) DEFAULT NULL, denomination VARCHAR(255) DEFAULT NULL, statut VARCHAR(255) DEFAULT NULL, date_nais DATETIME DEFAULT NULL, INDEX IDX_C74404554350F055 (cate_client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conditionnement (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, libelle VARCHAR(255) NOT NULL, qte NUMERIC(10, 2) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conditionner (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, conditionnement_id INT NOT NULL, prix_min NUMERIC(30, 2) DEFAULT NULL, prix_max NUMERIC(30, 2) DEFAULT NULL, prix_concurentiel NUMERIC(30, 2) DEFAULT NULL, prix_achat NUMERIC(30, 2) DEFAULT NULL, prix_revient NUMERIC(30, 2) DEFAULT NULL, qte_produit NUMERIC(10, 2) DEFAULT NULL, prix_vente NUMERIC(30, 2) DEFAULT NULL, INDEX IDX_F5BFE64CF347EFB (produit_id), INDEX IDX_F5BFE64CA222637 (conditionnement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conditionner_cate_client (id INT AUTO_INCREMENT NOT NULL, cate_client_id INT NOT NULL, conditionner_id INT NOT NULL, prix_min NUMERIC(30, 2) DEFAULT NULL, prix_max NUMERIC(30, 2) DEFAULT NULL, prix_vente NUMERIC(30, 2) DEFAULT NULL, INDEX IDX_D03593F4350F055 (cate_client_id), INDEX IDX_D03593F4C3B4B16 (conditionner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, utilisateur_id INT DEFAULT NULL, chemin VARCHAR(255) NOT NULL, nom_prod VARCHAR(255) NOT NULL, INDEX IDX_6A2CA10CF347EFB (produit_id), INDEX IDX_6A2CA10CFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mode_def (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prix (id INT AUTO_INCREMENT NOT NULL, conditionnement_id INT DEFAULT NULL, conditionner_client_id INT DEFAULT NULL, conditionner_id INT DEFAULT NULL, client_id INT DEFAULT NULL, prix_min NUMERIC(30, 2) DEFAULT NULL, prix_max NUMERIC(30, 2) DEFAULT NULL, date_attribution DATETIME NOT NULL, date_fin DATETIME DEFAULT NULL, est_actif VARBINARY(255) DEFAULT NULL, prix_concurentiel NUMERIC(30, 2) DEFAULT NULL, prix_achat NUMERIC(30, 2) DEFAULT NULL, prix_revient NUMERIC(30, 2) DEFAULT NULL, prix_vente NUMERIC(30, 2) DEFAULT NULL, INDEX IDX_F7EFEA5EA222637 (conditionnement_id), INDEX IDX_F7EFEA5EA04D0606 (conditionner_client_id), INDEX IDX_F7EFEA5E4C3B4B16 (conditionner_id), INDEX IDX_F7EFEA5E19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, sous_categorie_id INT DEFAULT NULL, mode_id INT DEFAULT NULL, designation VARCHAR(255) NOT NULL, code VARCHAR(255) DEFAULT NULL, ref_usine VARCHAR(255) DEFAULT NULL, a_taxe VARBINARY(255) NOT NULL, description LONGTEXT DEFAULT NULL, prix_achat NUMERIC(30, 2) DEFAULT NULL, prix_revient NUMERIC(30, 2) DEFAULT NULL, qte_unite_de_mesure NUMERIC(30, 2) DEFAULT NULL, prix_vente NUMERIC(30, 2) DEFAULT NULL, INDEX IDX_29A5EC27365BF48 (sous_categorie_id), INDEX IDX_29A5EC2777E5854A (mode_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sous_categorie (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_52743D7BBCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, sexe VARCHAR(255) DEFAULT NULL, date_naissance DATETIME DEFAULT NULL, adresse LONGTEXT DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur_role (utilisateur_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_9EE8E650FB88E14F (utilisateur_id), INDEX IDX_9EE8E650D60322AC (role_id), PRIMARY KEY(utilisateur_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404554350F055 FOREIGN KEY (cate_client_id) REFERENCES categ_client (id)');
        $this->addSql('ALTER TABLE conditionner ADD CONSTRAINT FK_F5BFE64CF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE conditionner ADD CONSTRAINT FK_F5BFE64CA222637 FOREIGN KEY (conditionnement_id) REFERENCES conditionnement (id)');
        $this->addSql('ALTER TABLE conditionner_cate_client ADD CONSTRAINT FK_D03593F4350F055 FOREIGN KEY (cate_client_id) REFERENCES categ_client (id)');
        $this->addSql('ALTER TABLE conditionner_cate_client ADD CONSTRAINT FK_D03593F4C3B4B16 FOREIGN KEY (conditionner_id) REFERENCES conditionner (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE prix ADD CONSTRAINT FK_F7EFEA5EA222637 FOREIGN KEY (conditionnement_id) REFERENCES conditionnement (id)');
        $this->addSql('ALTER TABLE prix ADD CONSTRAINT FK_F7EFEA5EA04D0606 FOREIGN KEY (conditionner_client_id) REFERENCES conditionner_cate_client (id)');
        $this->addSql('ALTER TABLE prix ADD CONSTRAINT FK_F7EFEA5E4C3B4B16 FOREIGN KEY (conditionner_id) REFERENCES conditionner (id)');
        $this->addSql('ALTER TABLE prix ADD CONSTRAINT FK_F7EFEA5E19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27365BF48 FOREIGN KEY (sous_categorie_id) REFERENCES sous_categorie (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2777E5854A FOREIGN KEY (mode_id) REFERENCES mode_def (id)');
        $this->addSql('ALTER TABLE sous_categorie ADD CONSTRAINT FK_52743D7BBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE utilisateur_role ADD CONSTRAINT FK_9EE8E650FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_role ADD CONSTRAINT FK_9EE8E650D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404554350F055');
        $this->addSql('ALTER TABLE conditionner DROP FOREIGN KEY FK_F5BFE64CF347EFB');
        $this->addSql('ALTER TABLE conditionner DROP FOREIGN KEY FK_F5BFE64CA222637');
        $this->addSql('ALTER TABLE conditionner_cate_client DROP FOREIGN KEY FK_D03593F4350F055');
        $this->addSql('ALTER TABLE conditionner_cate_client DROP FOREIGN KEY FK_D03593F4C3B4B16');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CF347EFB');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CFB88E14F');
        $this->addSql('ALTER TABLE prix DROP FOREIGN KEY FK_F7EFEA5EA222637');
        $this->addSql('ALTER TABLE prix DROP FOREIGN KEY FK_F7EFEA5EA04D0606');
        $this->addSql('ALTER TABLE prix DROP FOREIGN KEY FK_F7EFEA5E4C3B4B16');
        $this->addSql('ALTER TABLE prix DROP FOREIGN KEY FK_F7EFEA5E19EB6921');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27365BF48');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2777E5854A');
        $this->addSql('ALTER TABLE sous_categorie DROP FOREIGN KEY FK_52743D7BBCF5E72D');
        $this->addSql('ALTER TABLE utilisateur_role DROP FOREIGN KEY FK_9EE8E650FB88E14F');
        $this->addSql('ALTER TABLE utilisateur_role DROP FOREIGN KEY FK_9EE8E650D60322AC');
        $this->addSql('DROP TABLE categ_client');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE conditionnement');
        $this->addSql('DROP TABLE conditionner');
        $this->addSql('DROP TABLE conditionner_cate_client');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE mode_def');
        $this->addSql('DROP TABLE prix');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE sous_categorie');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE utilisateur_role');
    }
}
