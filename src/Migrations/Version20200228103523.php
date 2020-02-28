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
        $this->addSql('INSERT INTO `article` (`id`, `product_id`, `stock`, `size`, `color`, `device`, `creation_date`) VALUES (1, 1, 25, NULL, NULL, NULL, "2020-02-28 10:36:00"),  (2, 2, 25, NULL, NULL, NULL, "2020-02-28 10:37:00"),
        (3, 3, 10, NULL, NULL, NULL, "2020-02-28 10:41:00"),
        (4, 4, 10, NULL, NULL, "iPhone 6/7/8", "2020-02-28 10:42:00"),
        (5, 4, 10, NULL, NULL, "Samsung Galaxy S8/S9", "2020-02-28 10:42:00"),
        (6, 5, 25, NULL, NULL, NULL, "2020-02-28 10:47:00"),
        (7, 6, 10, "37/40", "Black", NULL, "2020-02-28 10:52:00"),
        (8, 6, 10, "40/43", "Black", NULL, "2020-02-28 10:52:00"),
        (9, 7, 10, "S", "Black", NULL, "2020-02-28 10:52:00"),
        (10, 7, 10, "M", "Black", NULL, "2020-02-28 10:52:00"),
        (11, 7, 10, "L", "Black", NULL, "2020-02-28 10:52:00"),
        (12, 7, 10, "XL", "Black", NULL, "2020-02-28 10:52:00"),
        (13, 8, 10, "S", "White", NULL, "2020-02-28 10:52:00"),
        (14, 8, 10, "M", "White", NULL, "2020-02-28 10:52:00"),
        (15, 8, 10, "L", "White", NULL, "2020-02-28 10:52:00"),
        (16, 8, 10, "XL", "White", NULL, "2020-02-28 10:52:00"),
        (17, 8, 10, "S", "Black", NULL, "2020-02-28 10:52:00"),
        (18, 8, 10, "M", "Black", NULL, "2020-02-28 10:52:00"),
        (19, 8, 10, "L", "Black", NULL, "2020-02-28 10:52:00"),
        (20, 8, 10, "XL", "Black", NULL, "2020-02-28 10:52:00")') ;
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
