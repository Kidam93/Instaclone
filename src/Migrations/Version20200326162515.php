<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200326162515 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE reputation_wall (reputation_id INT NOT NULL, wall_id INT NOT NULL, INDEX IDX_A2AD5E5E54266CA2 (reputation_id), INDEX IDX_A2AD5E5EC33923F1 (wall_id), PRIMARY KEY(reputation_id, wall_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reputation_wall ADD CONSTRAINT FK_A2AD5E5E54266CA2 FOREIGN KEY (reputation_id) REFERENCES reputation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reputation_wall ADD CONSTRAINT FK_A2AD5E5EC33923F1 FOREIGN KEY (wall_id) REFERENCES wall (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE reputation_wall');
    }
}
