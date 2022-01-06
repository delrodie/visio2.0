<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220106131830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, monture_id INT DEFAULT NULL, type_verre_id INT DEFAULT NULL, numero VARCHAR(255) DEFAULT NULL, montant_ht INT DEFAULT NULL, remise INT DEFAULT NULL, tva INT DEFAULT NULL, montant_ttc INT DEFAULT NULL, accompte INT DEFAULT NULL, rap INT DEFAULT NULL, part_assurance INT DEFAULT NULL, date VARCHAR(255) DEFAULT NULL, odcyl VARCHAR(255) DEFAULT NULL, od_axe VARCHAR(255) DEFAULT NULL, od_add VARCHAR(255) DEFAULT NULL, od_qte VARCHAR(255) DEFAULT NULL, od_montant INT DEFAULT NULL, od_sph VARCHAR(255) DEFAULT NULL, og_cyl VARCHAR(255) DEFAULT NULL, og_axe VARCHAR(255) DEFAULT NULL, og_add VARCHAR(255) DEFAULT NULL, og_qte INT DEFAULT NULL, og_montant INT DEFAULT NULL, og_sph VARCHAR(255) DEFAULT NULL, prix_monture INT DEFAULT NULL, statut TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_FE86641019EB6921 (client_id), INDEX IDX_FE866410D40ADBBC (monture_id), INDEX IDX_FE8664104B6C07B6 (type_verre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641019EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410D40ADBBC FOREIGN KEY (monture_id) REFERENCES monture (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664104B6C07B6 FOREIGN KEY (type_verre_id) REFERENCES type_verre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE facture');
    }
}
