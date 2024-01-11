<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231206230212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE type_hour (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_hour (id INT AUTO_INCREMENT NOT NULL, developper_id INT DEFAULT NULL, type_hour_id INT DEFAULT NULL, start_time DATETIME NOT NULL, end_time DATETIME NOT NULL, hours_worked NUMERIC(10, 2) NOT NULL, unit_price NUMERIC(10, 2) NOT NULL, INDEX IDX_89DDA076DA42B93 (developper_id), INDEX IDX_89DDA0769DB2140C (type_hour_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE work_hour ADD CONSTRAINT FK_89DDA076DA42B93 FOREIGN KEY (developper_id) REFERENCES developer (id)');
        $this->addSql('ALTER TABLE work_hour ADD CONSTRAINT FK_89DDA0769DB2140C FOREIGN KEY (type_hour_id) REFERENCES type_hour (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE work_hour DROP FOREIGN KEY FK_89DDA076DA42B93');
        $this->addSql('ALTER TABLE work_hour DROP FOREIGN KEY FK_89DDA0769DB2140C');
        $this->addSql('DROP TABLE type_hour');
        $this->addSql('DROP TABLE work_hour');
    }
}
