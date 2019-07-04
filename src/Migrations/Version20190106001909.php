<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190106001909 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tournament (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, tournament_desc VARCHAR(1000) DEFAULT NULL, rounds SMALLINT DEFAULT NULL, playoffs_places SMALLINT DEFAULT NULL, rounds_per_pair SMALLINT DEFAULT NULL, group_count SMALLINT DEFAULT NULL, players_on_field SMALLINT NOT NULL, rules VARCHAR(1000) DEFAULT NULL, prizes VARCHAR(255) DEFAULT NULL, region VARCHAR(255) NOT NULL, venue VARCHAR(255) DEFAULT NULL, contacts VARCHAR(255) DEFAULT NULL, code VARCHAR(9) NOT NULL, is_started TINYINT(1) NOT NULL, is_ended TINYINT(1) NOT NULL, INDEX IDX_BD5FB8D9F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament_type (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tournament ADD CONSTRAINT FK_BD5FB8D9F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE tournament');
        $this->addSql('DROP TABLE tournament_type');
    }
}
