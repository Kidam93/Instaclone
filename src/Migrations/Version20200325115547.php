<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200325115547 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE wall (id INT AUTO_INCREMENT NOT NULL, comment LONGTEXT NOT NULL, date DATETIME NOT NULL, file VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wall_user (wall_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4B3EA268C33923F1 (wall_id), INDEX IDX_4B3EA268A76ED395 (user_id), PRIMARY KEY(wall_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE wall_user ADD CONSTRAINT FK_4B3EA268C33923F1 FOREIGN KEY (wall_id) REFERENCES wall (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wall_user ADD CONSTRAINT FK_4B3EA268A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE wall_user DROP FOREIGN KEY FK_4B3EA268C33923F1');
        $this->addSql('DROP TABLE wall');
        $this->addSql('DROP TABLE wall_user');
    }
}
