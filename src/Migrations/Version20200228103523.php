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
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
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
        (9, 'Underwears', 1),
        (10, 'Totebags', 2),
        (11, 'Beanies', 2),
        (12, 'Phone Cases', 2),
        (13, 'Socks', 2),
        (14, 'Gourds', 2),
        (15, 'Books', 4),
        (16, 'Scarves', 2),
        (17, 'Collection', 0),
        (18, 'Full Stack', 17),
        ");
        $this->addSql("INSERT INTO `price` (`id`, `price_df`) VALUES
        (1, 19.99),
        (2, 19.99),
        (3, 11.5),
        (4, 16),
        (5, 18.99),
        (6, 5.99),
        (7, 9.99),
        (8, 5.99)");
        $this->addSql("INSERT INTO `product` (`id`, `price_id`, `title`, `description`, `category_id`) VALUES
        (1, 1, 'L\'écharpe C#', 'A beautiful scarf made for the best C# developpers.\r\nWear it everywhere and be proud !\r\n', 16),
        (2, 2, 'Le bon Bonnet', 'A cool beanie for everybody, bald men will enjoy it ! ', 11),
        (3, 3, 'Guidebook \"How to fix your functions\"', 'Tired of functions errors ? Then this guidebook is for you ! This book contains everything you need to know to fix errors in your functions !', 15),
        (4, 4, '\"Protected\" protective case', 'With this protective case, your phone will stay safe !', 12),
        (5, 5, 'Mug isHot', 'Amazing thermal mug isHot coded in JavaScript. Its background color changes according to the heat of its beverage.', 6),
        (6, 6, 'Les bonnes black socks', 'Perfect socks for people who actually dont like having shoes at work, especially if you\'re a developer', 13),
        (7, 7, 'Le bon black t-Shirt', 'Developer with dadbod ? No problem, be proud of yourself no matter who you are, you rock!', 7),
        (8, 8, 'Le bon black Underwear', 'It\'s always nice to wear underwear, at least when you\'re in a public place, even though they\'ll never know :)', 9)");
        $this->addSql("INSERT INTO `article` (`id`, `product_id`, `stock`, `size`, `color`, `device`, `creation_date`) VALUES
        (1, 1, 25, NULL, NULL, NULL, '2020-02-28 10:36:00'),
        (2, 2, 25, NULL, NULL, NULL, '2020-02-28 10:37:00'),
        (3, 3, 10, NULL, NULL, NULL, '2020-02-28 10:41:00'),
        (4, 4, 10, NULL, NULL, 'iPhone 6/7/8', '2020-02-28 10:42:00'),
        (5, 4, 10, NULL, NULL, 'Samsung Galaxy S8/S9', '2020-02-28 10:42:00'),
        (6, 5, 25, NULL, NULL, NULL, '2020-02-28 10:47:00'),
        (7, 6, 10, '37/40', 'Black', NULL, '2020-02-28 10:52:00'),
        (8, 6, 10, '40/43', 'Black', NULL, '2020-02-28 10:52:00'),
        (9, 7, 10, 'S', 'Black', NULL, '2020-02-28 10:52:00'),
        (10, 7, 10, 'M', 'Black', NULL, '2020-02-28 10:52:00'),
        (11, 7, 10, 'L', 'Black', NULL, '2020-02-28 10:52:00'),
        (12, 7, 10, 'XL', 'Black', NULL, '2020-02-28 10:52:00'),
        (13, 8, 10, 'S', 'White', NULL, '2020-02-28 10:52:00'),
        (14, 8, 10, 'M', 'White', NULL, '2020-02-28 10:52:00'),
        (15, 8, 10, 'L', 'White', NULL, '2020-02-28 10:52:00'),
        (16, 8, 10, 'XL', 'White', NULL, '2020-02-28 10:52:00'),
        (17, 8, 10, 'S', 'Black', NULL, '2020-02-28 10:52:00'),
        (18, 8, 10, 'M', 'Black', NULL, '2020-02-28 10:52:00'),
        (19, 8, 10, 'L', 'Black', NULL, '2020-02-28 10:52:00'),
        (20, 8, 10, 'XL', 'Black', NULL, '2020-02-28 10:52:00')");
        $this->addSql("INSERT INTO `article_images` (`id`, `article_id`, `url`) VALUES
        (1, 1, 'https://ibb.co/xHsZbXc'),
        (2, 2, 'https://ibb.co/0hCqB2t'),
        (3, 3, 'https://cdn.discordapp.com/attachments/682142335601344612/682869509731778590/large_book.png'),
        (4, 4, 'https://cdn.discordapp.com/attachments/682142335601344612/682885954398060554/large_protective_case.png'),
        (5, 5, 'https://cdn.discordapp.com/attachments/682142335601344612/682885954398060554/large_protective_case.png'),
        (6, 6, 'https://ibb.co/PZ0ntcK'),
        (7, 7, 'https://ibb.co/Kw7BPLS'),
        (8, 8, 'https://ibb.co/Kw7BPLS'),
        (9, 9, 'https://ibb.co/98fPB7p'),
        (10, 10, 'https://ibb.co/98fPB7p'),
        (11, 11, 'https://ibb.co/98fPB7p'),
        (12, 12, 'https://ibb.co/98fPB7p'),
        (13, 13, 'https://ibb.co/RzN1mFM'),
        (14, 14, 'https://ibb.co/RzN1mFM'),
        (15, 15, 'https://ibb.co/RzN1mFM'),
        (16, 16, 'https://ibb.co/RzN1mFM'),
        (17, 17, 'https://ibb.co/m512Hfg'),
        (18, 18, 'https://ibb.co/m512Hfg'),
        (19, 19, 'https://ibb.co/m512Hfg'),
        (20, 20, 'https://ibb.co/m512Hfg')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE adress (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, country VARCHAR(45) NOT NULL, city VARCHAR(100) NOT NULL, zip VARCHAR(45) NOT NULL, address VARCHAR(255) NOT NULL, optional_info VARCHAR(100) DEFAULT NULL, default_adress TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_5CECC7BE9B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, order_number VARCHAR(100) DEFAULT NULL, validation_date DATETIME DEFAULT NULL, INDEX IDX_E52FFDEE9B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders_article (orders_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_F34F7C1DCFFE9AD6 (orders_id), INDEX IDX_F34F7C1D7294869C (article_id), PRIMARY KEY(orders_id, article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adress ADD CONSTRAINT FK_5CECC7BE9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE orders_article ADD CONSTRAINT FK_F34F7C1DCFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orders_article ADD CONSTRAINT FK_F34F7C1D7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
    }
}
