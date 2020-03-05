<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200305081157 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category ADD image VARCHAR(255) DEFAULT NULL');

        $imageLinks = [
            "NULL", "NULL", "NULL", "NULL", '"https://i.ibb.co/7XmfDJS/classic-mugs.jpg"',
            '"https://i.ibb.co/vD3hRwD/thermic-mug.jpg"',
            '"https://i.ibb.co/4W1DDqW/t-shirts.jpg"',
            '"https://i.ibb.co/5Y4Grwb/hoodies.jpg"',
            '"https://i.ibb.co/DM2RxdR/underwears.jpg"',
            '"https://i.ibb.co/vDvjYSS/totebags.jpg"',
            '"https://i.ibb.co/1TWd9X8/beanies.jpg"',
            '"https://i.ibb.co/xzGjmrg/phone-cases.jpg"',
            '"https://i.ibb.co/74Y21T7/socks.jpg"',
            '"https://i.ibb.co/8smg96K/gourds.jpg"',
            '"https://i.ibb.co/5RrcyQp/books.jpg"',
            '"https://i.ibb.co/C0kdXGr/scarfs.jpg"',
            "NULL",
            '"https://i.ibb.co/Jx3q4MK/full-stack.jpg"'
        ];

        for ($i = 0; $i < count($imageLinks); $i++) {
            $idvalue = $i + 1;
            $this->addSql("UPDATE category  SET `image` = $imageLinks[$i] WHERE id = $idvalue");
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE account CHANGE newsletter newsletter TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE adress CHANGE default_adress default_adress TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE category DROP image');
    }
}
