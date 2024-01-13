<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240113122459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity_option (id INT AUTO_INCREMENT NOT NULL, options_id INT DEFAULT NULL, activities_id INT DEFAULT NULL, INDEX IDX_8F0E0B03ADB05F1 (options_id), INDEX IDX_8F0E0B02A4DB562 (activities_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity_option ADD CONSTRAINT FK_8F0E0B03ADB05F1 FOREIGN KEY (options_id) REFERENCES `option` (id)');
        $this->addSql('ALTER TABLE activity_option ADD CONSTRAINT FK_8F0E0B02A4DB562 FOREIGN KEY (activities_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE `option` DROP FOREIGN KEY FK_5A8600B081C06096');
        $this->addSql('DROP INDEX IDX_5A8600B081C06096 ON `option`');
        $this->addSql('ALTER TABLE `option` DROP activity_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_option DROP FOREIGN KEY FK_8F0E0B03ADB05F1');
        $this->addSql('ALTER TABLE activity_option DROP FOREIGN KEY FK_8F0E0B02A4DB562');
        $this->addSql('DROP TABLE activity_option');
        $this->addSql('ALTER TABLE `option` ADD activity_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `option` ADD CONSTRAINT FK_5A8600B081C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_5A8600B081C06096 ON `option` (activity_id)');
    }
}
