<?php

declare(strict_types=1);

namespace App\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261124155226 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE VSWPG_GrafanaDatasourceExporters (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, description LONGTEXT DEFAULT NULL, link VARCHAR(128) DEFAULT NULL, datasource_id INT DEFAULT NULL, INDEX IDX_D2104211712290DD (datasource_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE VSWPG_GrafanaDatasources (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, description LONGTEXT DEFAULT NULL, link VARCHAR(128) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('ALTER TABLE VSWPG_GrafanaDatasourceExporters ADD CONSTRAINT FK_D2104211712290DD FOREIGN KEY (datasource_id) REFERENCES VSWPG_GrafanaDatasources (id)');
        $this->addSql('ALTER TABLE VSWPG_Projects CHANGE source_type source_type ENUM(\'wget\', \'git\', \'svn\', \'install_manual\') DEFAULT \'wget\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE VSWPG_GrafanaDatasourceExporters DROP FOREIGN KEY FK_D2104211712290DD');
        $this->addSql('DROP TABLE VSWPG_GrafanaDatasourceExporters');
        $this->addSql('DROP TABLE VSWPG_GrafanaDatasources');
        $this->addSql('ALTER TABLE VSWPG_Projects CHANGE source_type source_type ENUM(\'wget\', \'git\', \'svn\', \'install_manual\') DEFAULT NULL');
    }
}
