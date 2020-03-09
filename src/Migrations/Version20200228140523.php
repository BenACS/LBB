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
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $allURL = ['"https://i.ibb.co/4dfkLKD/Large-Red-Scarf.png"', '"https://cdn.discordapp.com/attachments/682142335601344612/685418590261870592/large-Beanie.png"', '"https://cdn.discordapp.com/attachments/682142335601344612/682869509731778590/large_book.png"', '"https://cdn.discordapp.com/attachments/682142335601344612/682885954398060554/large_protective_case.png"', '"https://cdn.discordapp.com/attachments/682142335601344612/682885954398060554/large_protective_case.png"', '"https://i.ibb.co/Y7wMbcm/mug-is-Hot.png"', '"https://i.ibb.co/PQ4JL90/black-Socks.png"', '"https://i.ibb.co/PQ4JL90/black-Socks.png"', '"https://i.ibb.co/RvM8tqg/blacktshirt.png"', '"https://i.ibb.co/RvM8tqg/blacktshirt.png"', '"https://i.ibb.co/RvM8tqg/blacktshirt.png"', '"https://i.ibb.co/RvM8tqg/blacktshirt.png"', '"https://i.ibb.co/4P2cGqD/White-Underwear.png"', '"https://i.ibb.co/4P2cGqD/White-Underwear.png"', '"https://i.ibb.co/4P2cGqD/White-Underwear.png"', '"https://i.ibb.co/4P2cGqD/White-Underwear.png"', '"https://i.ibb.co/Jj0GzDg/Dark-Underwear.png"', '"https://i.ibb.co/Jj0GzDg/Dark-Underwear.png"', '"https://i.ibb.co/Jj0GzDg/Dark-Underwear.png"', '"https://i.ibb.co/Jj0GzDg/Dark-Underwear.png"'];

        for ($i = 0; $i < count($allURL); $i++) {
            $idvalue = $i + 1;
            $this->addSql("UPDATE article_images  SET `url` = $allURL[$i] WHERE id = $idvalue");
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
