<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200326161924 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE reputation (id INT AUTO_INCREMENT NOT NULL, likes INT NOT NULL, comments LONGTEXT DEFAULT NULL, date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reputation_user (reputation_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3CCB67E154266CA2 (reputation_id), INDEX IDX_3CCB67E1A76ED395 (user_id), PRIMARY KEY(reputation_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reputation_profil (reputation_id INT NOT NULL, profil_id INT NOT NULL, INDEX IDX_3C6EB63454266CA2 (reputation_id), INDEX IDX_3C6EB634275ED078 (profil_id), PRIMARY KEY(reputation_id, profil_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reputation_user ADD CONSTRAINT FK_3CCB67E154266CA2 FOREIGN KEY (reputation_id) REFERENCES reputation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reputation_user ADD CONSTRAINT FK_3CCB67E1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reputation_profil ADD CONSTRAINT FK_3C6EB63454266CA2 FOREIGN KEY (reputation_id) REFERENCES reputation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reputation_profil ADD CONSTRAINT FK_3C6EB634275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE reputation_user DROP FOREIGN KEY FK_3CCB67E154266CA2');
        $this->addSql('ALTER TABLE reputation_profil DROP FOREIGN KEY FK_3C6EB63454266CA2');
        $this->addSql('DROP TABLE reputation');
        $this->addSql('DROP TABLE reputation_user');
        $this->addSql('DROP TABLE reputation_profil');
    }
}
