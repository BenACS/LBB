<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200304141456 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, orders_id INT NOT NULL, article_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_BA388B7CFFE9AD6 (orders_id), INDEX IDX_BA388B77294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B77294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('DROP TABLE orders_article');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE orders_article (orders_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_F34F7C1D7294869C (article_id), INDEX IDX_F34F7C1DCFFE9AD6 (orders_id), PRIMARY KEY(orders_id, article_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE orders_article ADD CONSTRAINT FK_F34F7C1D7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orders_article ADD CONSTRAINT FK_F34F7C1DCFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE cart');
        $this->addSql('ALTER TABLE account CHANGE newsletter newsletter TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE adress CHANGE default_adress default_adress TINYINT(1) DEFAULT \'0\' NOT NULL');
    }
}
