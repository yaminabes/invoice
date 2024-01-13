<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240113144232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity_supplement (activity_id INT NOT NULL, supplement_id INT NOT NULL, INDEX IDX_54B84DC181C06096 (activity_id), INDEX IDX_54B84DC17793FA21 (supplement_id), PRIMARY KEY(activity_id, supplement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity_supplement ADD CONSTRAINT FK_54B84DC181C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE activity_supplement ADD CONSTRAINT FK_54B84DC17793FA21 FOREIGN KEY (supplement_id) REFERENCES supplement (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_supplement DROP FOREIGN KEY FK_54B84DC181C06096');
        $this->addSql('ALTER TABLE activity_supplement DROP FOREIGN KEY FK_54B84DC17793FA21');
        $this->addSql('DROP TABLE activity_supplement');
    }
}
