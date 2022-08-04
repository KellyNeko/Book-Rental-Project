<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220804142024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO category (`label`) VALUES ("roman")');
        $this->addSql('INSERT INTO category (`label`) VALUES ("essai")');
        $this->addSql('INSERT INTO category (`label`) VALUES ("recueil")');

        $this->addSql('INSERT INTO author (`last_name`, `first_name`) VALUES ("Camus","Albert")');
        $this->addSql('INSERT INTO author (`last_name`, `first_name`) VALUES ("Proust","Marcel")');
        $this->addSql('INSERT INTO author (`last_name`, `first_name`) VALUES ("Kafka","Franz")');
        $this->addSql('INSERT INTO author (`last_name`, `first_name`) VALUES ("De Beauvoir","Simone")');
        $this->addSql('INSERT INTO author (`last_name`, `first_name`) VALUES ("Prévert","Jacques")');

        $this->addSql('INSERT INTO book (`reference`, `author_id`) VALUES ("L\'Étranger",1)');
        $this->addSql('INSERT INTO book (`reference`, `author_id`) VALUES ("À la recherche du temps perdu",2)');
        $this->addSql('INSERT INTO book (`reference`, `author_id`) VALUES ("Le Procès",3)');
        $this->addSql('INSERT INTO book (`reference`, `author_id`) VALUES ("Le Deuxième Sexe",4)');
        $this->addSql('INSERT INTO book (`reference`, `author_id`) VALUES ("Paroles",5)');

        $this->addSql('INSERT INTO book_category (`category_id`, `book_id`) VALUES (1,1)');
        $this->addSql('INSERT INTO book_category (`category_id`, `book_id`) VALUES (1,2)');
        $this->addSql('INSERT INTO book_category (`category_id`, `book_id`) VALUES (1,3)');
        $this->addSql('INSERT INTO book_category (`category_id`, `book_id`) VALUES (2,4)');
        $this->addSql('INSERT INTO book_category (`category_id`, `book_id`) VALUES (3,5)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
