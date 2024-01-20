<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240120131334 extends AbstractMigration
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
        $this->addSql('ALTER TABLE activity_option DROP FOREIGN KEY FK_8F0E0B02A4DB562');
        $this->addSql('ALTER TABLE activity_option DROP FOREIGN KEY FK_8F0E0B03ADB05F1');
        $this->addSql('DROP TABLE `option`');
        $this->addSql('DROP TABLE activity_option');
        $this->addSql('ALTER TABLE user ADD responsable_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64953C59D72 FOREIGN KEY (responsable_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64953C59D72 ON user (responsable_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `option` (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, percentage NUMERIC(5, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE activity_option (id INT AUTO_INCREMENT NOT NULL, options_id INT DEFAULT NULL, activities_id INT DEFAULT NULL, INDEX IDX_8F0E0B02A4DB562 (activities_id), INDEX IDX_8F0E0B03ADB05F1 (options_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE activity_option ADD CONSTRAINT FK_8F0E0B02A4DB562 FOREIGN KEY (activities_id) REFERENCES activity (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE activity_option ADD CONSTRAINT FK_8F0E0B03ADB05F1 FOREIGN KEY (options_id) REFERENCES `option` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE activity_supplement DROP FOREIGN KEY FK_54B84DC181C06096');
        $this->addSql('ALTER TABLE activity_supplement DROP FOREIGN KEY FK_54B84DC17793FA21');
        $this->addSql('DROP TABLE activity_supplement');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64953C59D72');
        $this->addSql('DROP INDEX IDX_8D93D64953C59D72 ON user');
        $this->addSql('ALTER TABLE user DROP responsable_id');
    }
}
