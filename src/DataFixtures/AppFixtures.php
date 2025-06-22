<?php
namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Actor;
use App\Entity\Director;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Жанры
        $genres = ["Action", "Comedy", "Drama", "Thriller", "Sci-Fi", "Horror"];
        foreach ($genres as $name) {
            $g = new Genre();
            $g->setName($name);
            $manager->persist($g);
        }

        // Актёры
        $actors = [
            "Leonardo DiCaprio",
            "Scarlett Johansson",
            "Morgan Freeman",
            "Natalie Portman",
        ];
        foreach ($actors as $name) {
            $a = new Actor();
            $a->setName($name);
            $manager->persist($a);
        }

        // Режиссёры
        $directors = [
            "Christopher Nolan",
            "Quentin Tarantino",
            "Steven Spielberg",
        ];
        foreach ($directors as $name) {
            $d = new Director();
            $d->setName($name);
            $manager->persist($d);
        }

        $manager->flush();
    }
}
