<?php

declare(strict_types=1);

namespace App\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250119075407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE VSWPG_Categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE VSWPG_PhpbrewExtensions (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(32) NOT NULL, description VARCHAR(255) NOT NULL, github_repo VARCHAR(128) NOT NULL, branch VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_8652D405E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE VSWPG_Projects (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(128) NOT NULL, description LONGTEXT DEFAULT NULL, source_type enum(\'wget\', \'git\', \'svn\', \'install_manual\'), repository VARCHAR(128) DEFAULT NULL, branch VARCHAR(32) DEFAULT NULL, project_root VARCHAR(128) NOT NULL, install_manual VARCHAR(255) DEFAULT NULL, predefinedType VARCHAR(64) DEFAULT NULL, predefinedTypeParams JSON DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, INDEX IDX_9B25501112469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE VSWPG_ProjectsHosts (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, host_type VARCHAR(32) NOT NULL, options JSON NOT NULL, host VARCHAR(255) NOT NULL, document_root VARCHAR(255) NOT NULL, with_ssl TINYINT(1) NOT NULL, INDEX IDX_23B22FF7166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE VSWPG_Projects ADD CONSTRAINT FK_9B25501112469DE2 FOREIGN KEY (category_id) REFERENCES VSWPG_Categories (id)');
        $this->addSql('ALTER TABLE VSWPG_ProjectsHosts ADD CONSTRAINT FK_23B22FF7166D1F9C FOREIGN KEY (project_id) REFERENCES VSWPG_Projects (id)');
        $this->addSql('ALTER TABLE VSAPP_Settings DROP FOREIGN KEY FK_4A491FD507FAB6A');
        $this->addSql('DROP INDEX IDX_4A491FD507FAB6A ON VSAPP_Settings');
        $this->addSql('ALTER TABLE VSAPP_Settings CHANGE maintenance_page_id maintenance_page_id  INT DEFAULT NULL');
        $this->addSql('ALTER TABLE VSAPP_Settings ADD CONSTRAINT FK_4A491FD507FAB6A FOREIGN KEY (maintenance_page_id ) REFERENCES VSCMS_Pages (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_4A491FD507FAB6A ON VSAPP_Settings (maintenance_page_id )');
        $this->addSql('ALTER TABLE VSUM_UsersInfo CHANGE title title ENUM(\'mr\', \'mrs\', \'miss\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE VSWPG_Projects DROP FOREIGN KEY FK_9B25501112469DE2');
        $this->addSql('ALTER TABLE VSWPG_ProjectsHosts DROP FOREIGN KEY FK_23B22FF7166D1F9C');
        $this->addSql('DROP TABLE VSWPG_Categories');
        $this->addSql('DROP TABLE VSWPG_PhpbrewExtensions');
        $this->addSql('DROP TABLE VSWPG_Projects');
        $this->addSql('DROP TABLE VSWPG_ProjectsHosts');
        $this->addSql('ALTER TABLE VSAPP_Settings DROP FOREIGN KEY FK_4A491FD507FAB6A');
        $this->addSql('DROP INDEX IDX_4A491FD507FAB6A ON VSAPP_Settings');
        $this->addSql('ALTER TABLE VSAPP_Settings CHANGE maintenance_page_id  maintenance_page_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE VSAPP_Settings ADD CONSTRAINT FK_4A491FD507FAB6A FOREIGN KEY (maintenance_page_id) REFERENCES VSCMS_Pages (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_4A491FD507FAB6A ON VSAPP_Settings (maintenance_page_id)');
        $this->addSql('ALTER TABLE VSUM_UsersInfo CHANGE title title VARCHAR(255) DEFAULT NULL');
    }
}
