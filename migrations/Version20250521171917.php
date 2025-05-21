<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250521171917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actor (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE director (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE director_actor (director_id INT NOT NULL, actor_id INT NOT NULL, PRIMARY KEY(director_id, actor_id))');
        $this->addSql('CREATE INDEX IDX_BE72C92B899FB366 ON director_actor (director_id)');
        $this->addSql('CREATE INDEX IDX_BE72C92B10DAF24A ON director_actor (actor_id)');
        $this->addSql('CREATE TABLE genre (id SERIAL NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE genre_actor (genre_id INT NOT NULL, actor_id INT NOT NULL, PRIMARY KEY(genre_id, actor_id))');
        $this->addSql('CREATE INDEX IDX_F973493C4296D31F ON genre_actor (genre_id)');
        $this->addSql('CREATE INDEX IDX_F973493C10DAF24A ON genre_actor (actor_id)');
        $this->addSql('CREATE TABLE movie (id SERIAL NOT NULL, title VARCHAR(255) NOT NULL, api_id VARCHAR(15) DEFAULT NULL, poster VARCHAR(255) DEFAULT NULL, description TEXT DEFAULT NULL, release_date DATE DEFAULT NULL, rating NUMERIC(3, 1) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE movie_actor (movie_id INT NOT NULL, actor_id INT NOT NULL, PRIMARY KEY(movie_id, actor_id))');
        $this->addSql('CREATE INDEX IDX_3A374C658F93B6FC ON movie_actor (movie_id)');
        $this->addSql('CREATE INDEX IDX_3A374C6510DAF24A ON movie_actor (actor_id)');
        $this->addSql('CREATE TABLE review (id SERIAL NOT NULL, movie_id INT NOT NULL, author_id INT NOT NULL, rating SMALLINT NOT NULL, content TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_794381C68F93B6FC ON review (movie_id)');
        $this->addSql('CREATE INDEX IDX_794381C6F675F31B ON review (author_id)');
        $this->addSql('COMMENT ON COLUMN review.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE director_actor ADD CONSTRAINT FK_BE72C92B899FB366 FOREIGN KEY (director_id) REFERENCES director (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE director_actor ADD CONSTRAINT FK_BE72C92B10DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE genre_actor ADD CONSTRAINT FK_F973493C4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE genre_actor ADD CONSTRAINT FK_F973493C10DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE movie_actor ADD CONSTRAINT FK_3A374C658F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE movie_actor ADD CONSTRAINT FK_3A374C6510DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C68F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE director_actor DROP CONSTRAINT FK_BE72C92B899FB366');
        $this->addSql('ALTER TABLE director_actor DROP CONSTRAINT FK_BE72C92B10DAF24A');
        $this->addSql('ALTER TABLE genre_actor DROP CONSTRAINT FK_F973493C4296D31F');
        $this->addSql('ALTER TABLE genre_actor DROP CONSTRAINT FK_F973493C10DAF24A');
        $this->addSql('ALTER TABLE movie_actor DROP CONSTRAINT FK_3A374C658F93B6FC');
        $this->addSql('ALTER TABLE movie_actor DROP CONSTRAINT FK_3A374C6510DAF24A');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C68F93B6FC');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6F675F31B');
        $this->addSql('DROP TABLE actor');
        $this->addSql('DROP TABLE director');
        $this->addSql('DROP TABLE director_actor');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE genre_actor');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE movie_actor');
        $this->addSql('DROP TABLE review');
    }
}
