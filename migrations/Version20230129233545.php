<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230129233545 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_order DROP CONSTRAINT fk_df8e88488d9f6d38');
        $this->addSql('ALTER TABLE item_order DROP CONSTRAINT fk_df8e8848126f525e');
        $this->addSql('DROP TABLE item_order');
        $this->addSql('ALTER TABLE "order" ADD items_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F52993986BB0AE84 FOREIGN KEY (items_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F52993986BB0AE84 ON "order" (items_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE item_order (item_id INT NOT NULL, order_id INT NOT NULL, PRIMARY KEY(item_id, order_id))');
        $this->addSql('CREATE INDEX idx_df8e88488d9f6d38 ON item_order (order_id)');
        $this->addSql('CREATE INDEX idx_df8e8848126f525e ON item_order (item_id)');
        $this->addSql('ALTER TABLE item_order ADD CONSTRAINT fk_df8e88488d9f6d38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_order ADD CONSTRAINT fk_df8e8848126f525e FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F52993986BB0AE84');
        $this->addSql('DROP INDEX IDX_F52993986BB0AE84');
        $this->addSql('ALTER TABLE "order" DROP items_id');
    }
}
