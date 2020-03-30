<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200227145309 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE adress (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, country VARCHAR(45) NOT NULL, city VARCHAR(100) NOT NULL, zip VARCHAR(45) NOT NULL, address VARCHAR(255) NOT NULL, optional_info VARCHAR(100) DEFAULT NULL, default_adress TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_5CECC7BE9B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, order_number VARCHAR(100) DEFAULT NULL, validation_date DATETIME DEFAULT NULL, INDEX IDX_E52FFDEE9B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders_article (orders_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_F34F7C1DCFFE9AD6 (orders_id), INDEX IDX_F34F7C1D7294869C (article_id), PRIMARY KEY(orders_id, article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adress ADD CONSTRAINT FK_5CECC7BE9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE orders_article ADD CONSTRAINT FK_F34F7C1DCFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orders_article ADD CONSTRAINT FK_F34F7C1D7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE orders_article DROP FOREIGN KEY FK_F34F7C1DCFFE9AD6');
        $this->addSql('DROP TABLE adress');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE orders_article');
    }
}
