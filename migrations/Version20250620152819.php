<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250620152819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE movie_genre (movie_id INT NOT NULL, genre_id INT NOT NULL, PRIMARY KEY(movie_id, genre_id))');
        $this->addSql('CREATE INDEX IDX_FD1229648F93B6FC ON movie_genre (movie_id)');
        $this->addSql('CREATE INDEX IDX_FD1229644296D31F ON movie_genre (genre_id)');
        $this->addSql('CREATE TABLE movie_director (movie_id INT NOT NULL, director_id INT NOT NULL, PRIMARY KEY(movie_id, director_id))');
        $this->addSql('CREATE INDEX IDX_C266487D8F93B6FC ON movie_director (movie_id)');
        $this->addSql('CREATE INDEX IDX_C266487D899FB366 ON movie_director (director_id)');
        $this->addSql('ALTER TABLE movie_genre ADD CONSTRAINT FK_FD1229648F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE movie_genre ADD CONSTRAINT FK_FD1229644296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE movie_director ADD CONSTRAINT FK_C266487D8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE movie_director ADD CONSTRAINT FK_C266487D899FB366 FOREIGN KEY (director_id) REFERENCES director (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE director_actor DROP CONSTRAINT fk_be72c92b899fb366');
        $this->addSql('ALTER TABLE director_actor DROP CONSTRAINT fk_be72c92b10daf24a');
        $this->addSql('ALTER TABLE genre_actor DROP CONSTRAINT fk_f973493c4296d31f');
        $this->addSql('ALTER TABLE genre_actor DROP CONSTRAINT fk_f973493c10daf24a');
        $this->addSql('DROP TABLE director_actor');
        $this->addSql('DROP TABLE genre_actor');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE director_actor (director_id INT NOT NULL, actor_id INT NOT NULL, PRIMARY KEY(director_id, actor_id))');
        $this->addSql('CREATE INDEX idx_be72c92b10daf24a ON director_actor (actor_id)');
        $this->addSql('CREATE INDEX idx_be72c92b899fb366 ON director_actor (director_id)');
        $this->addSql('CREATE TABLE genre_actor (genre_id INT NOT NULL, actor_id INT NOT NULL, PRIMARY KEY(genre_id, actor_id))');
        $this->addSql('CREATE INDEX idx_f973493c10daf24a ON genre_actor (actor_id)');
        $this->addSql('CREATE INDEX idx_f973493c4296d31f ON genre_actor (genre_id)');
        $this->addSql('ALTER TABLE director_actor ADD CONSTRAINT fk_be72c92b899fb366 FOREIGN KEY (director_id) REFERENCES director (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE director_actor ADD CONSTRAINT fk_be72c92b10daf24a FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE genre_actor ADD CONSTRAINT fk_f973493c4296d31f FOREIGN KEY (genre_id) REFERENCES genre (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE genre_actor ADD CONSTRAINT fk_f973493c10daf24a FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE movie_genre DROP CONSTRAINT FK_FD1229648F93B6FC');
        $this->addSql('ALTER TABLE movie_genre DROP CONSTRAINT FK_FD1229644296D31F');
        $this->addSql('ALTER TABLE movie_director DROP CONSTRAINT FK_C266487D8F93B6FC');
        $this->addSql('ALTER TABLE movie_director DROP CONSTRAINT FK_C266487D899FB366');
        $this->addSql('DROP TABLE movie_genre');
        $this->addSql('DROP TABLE movie_director');
    }
}
