<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200228140523 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
            $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
            $this->addSql("INSERT INTO `article_images` (`id`, `article_id`, `url`) VALUES
            (1, 1, 'https://i.ibb.co/4dfkLKD/Large-Red-Scarf.png'),
            (2, 2, 'https://i.ibb.co/NZ9x1CS/large-Beanie.png'),
            (3, 3, 'https://cdn.discordapp.com/attachments/682142335601344612/682869509731778590/large_book.png'),
            (4, 4, 'https://cdn.discordapp.com/attachments/682142335601344612/682885954398060554/large_protective_case.png'),
            (5, 5, 'https://cdn.discordapp.com/attachments/682142335601344612/682885954398060554/large_protective_case.png'),
            (6, 6, 'https://i.ibb.co/Y7wMbcm/mug-is-Hot.png'),
            (7, 7, 'https://i.ibb.co/PQ4JL90/black-Socks.png'),
            (8, 8, 'https://i.ibb.co/PQ4JL90/black-Socks.png'),
            (9, 9, 'https://i.ibb.co/RvM8tqg/blacktshirt.png'),
            (10, 10, 'https://i.ibb.co/RvM8tqg/blacktshirt.png'),
            (11, 11, 'https://i.ibb.co/RvM8tqg/blacktshirt.png'),
            (12, 12, 'https://i.ibb.co/RvM8tqg/blacktshirt.png'),
            (13, 13, 'https://i.ibb.co/4P2cGqD/White-Underwear.png'),
            (14, 14, 'https://i.ibb.co/4P2cGqD/White-Underwear.png'),
            (15, 15, 'https://i.ibb.co/4P2cGqD/White-Underwear.png'),
            (16, 16, 'https://i.ibb.co/4P2cGqD/White-Underwear.png'),
            (17, 17, 'https://i.ibb.co/Jj0GzDg/Dark-Underwear.png'),
            (18, 18, 'https://i.ibb.co/Jj0GzDg/Dark-Underwear.png'),
            (19, 19, 'https://i.ibb.co/Jj0GzDg/Dark-Underwear.png'),
            (20, 20, 'https://i.ibb.co/Jj0GzDg/Dark-Underwear.png')");


    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("INSERT INTO `category` (`id`, `category_name`, `parent_id`) VALUES
        (17, 'Collections', 0),
        (18, 'Full Stack', 17)");
        $this->addSql("INSERT INTO `product_category` (`product_id`, `category_id`) VALUES
        (1, 16),
        (2, 11),
        (3, 15),
        (4, 12),
        (5, 6),
        (6, 13),
        (6, 18),
        (7, 7),
        (7, 18),
        (8, 9),
        (8, 18)");
    }
}
