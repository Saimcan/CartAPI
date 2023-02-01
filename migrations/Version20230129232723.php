<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230129232723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item_order (item_id INT NOT NULL, order_id INT NOT NULL, PRIMARY KEY(item_id, order_id))');
        $this->addSql('CREATE INDEX IDX_DF8E8848126F525E ON item_order (item_id)');
        $this->addSql('CREATE INDEX IDX_DF8E88488D9F6D38 ON item_order (order_id)');
        $this->addSql('ALTER TABLE item_order ADD CONSTRAINT FK_DF8E8848126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item_order ADD CONSTRAINT FK_DF8E88488D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE item DROP CONSTRAINT fk_1f1b251ef25ea799');
        $this->addSql('DROP INDEX idx_1f1b251ef25ea799');
        $this->addSql('ALTER TABLE item DROP order_placed_id');
        $this->addSql('ALTER TABLE "order" DROP items');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE item_order DROP CONSTRAINT FK_DF8E8848126F525E');
        $this->addSql('ALTER TABLE item_order DROP CONSTRAINT FK_DF8E88488D9F6D38');
        $this->addSql('DROP TABLE item_order');
        $this->addSql('ALTER TABLE "order" ADD items JSON NOT NULL');
        $this->addSql('ALTER TABLE item ADD order_placed_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT fk_1f1b251ef25ea799 FOREIGN KEY (order_placed_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_1f1b251ef25ea799 ON item (order_placed_id)');
    }
}
