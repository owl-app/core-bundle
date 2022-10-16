<?php

declare(strict_types=1);

namespace Owl\Bundle\CoreBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220621205132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE owl_equipment_refueling (id INT AUTO_INCREMENT NOT NULL, equipment_id INT NOT NULL, user_id INT NOT NULL, date DATE NOT NULL, mileage INT NOT NULL, quantity DOUBLE PRECISION NOT NULL, value INT NOT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_2494F8F5517FE9FE (equipment_id), INDEX IDX_2494F8F5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE owl_equipment_refueling ADD CONSTRAINT FK_2494F8F5517FE9FE FOREIGN KEY (equipment_id) REFERENCES owl_equipment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE owl_equipment_refueling ADD CONSTRAINT FK_2494F8F5A76ED395 FOREIGN KEY (user_id) REFERENCES owl_admin_user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE owl_equipment_refueling');
    }
}
