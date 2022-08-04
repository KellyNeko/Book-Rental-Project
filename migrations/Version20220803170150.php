<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220803170150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_renting DROP FOREIGN KEY FK_807314999D86650F');
        $this->addSql('DROP INDEX IDX_807314999D86650F ON book_renting');
        $this->addSql('ALTER TABLE book_renting CHANGE user_id_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE book_renting ADD CONSTRAINT FK_80731499A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_80731499A76ED395 ON book_renting (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_renting DROP FOREIGN KEY FK_80731499A76ED395');
        $this->addSql('DROP INDEX IDX_80731499A76ED395 ON book_renting');
        $this->addSql('ALTER TABLE book_renting CHANGE user_id user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE book_renting ADD CONSTRAINT FK_807314999D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_807314999D86650F ON book_renting (user_id_id)');
    }
}
