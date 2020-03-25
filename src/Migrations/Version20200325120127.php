<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200325120127 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE wall_profil (wall_id INT NOT NULL, profil_id INT NOT NULL, INDEX IDX_3D21D4FCC33923F1 (wall_id), INDEX IDX_3D21D4FC275ED078 (profil_id), PRIMARY KEY(wall_id, profil_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE wall_profil ADD CONSTRAINT FK_3D21D4FCC33923F1 FOREIGN KEY (wall_id) REFERENCES wall (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wall_profil ADD CONSTRAINT FK_3D21D4FC275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE wall_profil');
    }
}
