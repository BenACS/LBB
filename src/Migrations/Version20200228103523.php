<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200228103523 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO `category` (`id`, `category_name`, `parent_id`) VALUES
        (1, 'Clothes', 0),
        (2, 'Accessories', 0),
        (3, 'Mugs', 0),
        (4, 'Surprises', 0),
        (5, 'Classic', 3),
        (6, 'Thermic', 3),
        (7, 'T-shirts', 1),
        (8, 'Hoodies', 1),
        (9, 'Underwear', 1),
        (10, 'Totebag', 2),
        (11, 'Beanie', 2),
        (12, 'Phone Case', 2),
        (13, 'Socks', 2),
        (14, 'Gourd', 2),
        (15, 'Books', 4),
        (16, 'Scarfs', 2)");
        
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
