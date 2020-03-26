<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200304131642 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tag ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B78312469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_389B78312469DE2 ON tag (category_id)');
        $this->addSql("INSERT INTO `tag` (`id`, `tag_name`, `category_id`) VALUES
        (1, 'écharpe', 16),
        (2, 'foulard', 16),
        (3, 'scarf', 16),
        (4, 'bonnet', 11),
        (5, 'chapeau', 11),
        (6, 'hat', 11),
        (7, 'beanie', 11),
        (8, 'book', 15),
        (9, 'livre', 15),
        (10, 'guidebook', 15),
        (11, 'coque', 12),
        (12, 'téléphone', 12),
        (13, 'phonecase', 12),
        (14, 'protection', 12),
        (15, 'chaussettes', 13),
        (16, 'socks', 13),
        (17, 'tasse', 3),
        (18, 'mug', 3),
        (19, 'tshirt', 7),
        (20, 't-shirt', 7),
        (21, 'teeshirt', 7),
        (22, 'shirt', 7),
        (23, 'underwear', 9),
        (24, 'slip', 9),
        (25, 'boxer', 9),
        (26, 'caleçon', 9),
        (27, 'undies', 9),
        (28, 'panties', 9)");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE account CHANGE newsletter newsletter TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE adress CHANGE default_adress default_adress TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B78312469DE2');
        $this->addSql('DROP INDEX IDX_389B78312469DE2 ON tag');
        $this->addSql('ALTER TABLE tag DROP category_id');
    }
}
