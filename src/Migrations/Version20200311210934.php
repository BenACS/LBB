<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200311210934 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE article_images');
        $this->addSql('ALTER TABLE account CHANGE newsletter newsletter TINYINT(1) DEFAULT NULL, CHANGE register_date register_date DATETIME DEFAULT NULL, CHANGE role role VARCHAR(15) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article_images (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, url VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_8AD829EA7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE article_images ADD CONSTRAINT FK_8AD829EA3DA5256D FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE account CHANGE newsletter newsletter TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE register_date register_date DATETIME NOT NULL, CHANGE role role VARCHAR(15) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE adress CHANGE default_adress default_adress TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE price CHANGE price_df price_df DOUBLE PRECISION NOT NULL');
    }
}
