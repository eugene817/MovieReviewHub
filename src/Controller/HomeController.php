<?php
namespace App\Controller;

use App\Repository\MovieRepository;
use App\Repository\GenreRepository;
use App\Repository\ActorRepository;
use App\Repository\DirectorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route("/", name: "app_home", methods: ["GET"])]
    public function index(
        MovieRepository $movies,
        GenreRepository $genres,
        ActorRepository $actors,
        DirectorRepository $directors
    ): Response {
        return $this->render("home/index.html.twig", [
            "movieCount" => $movies->count([]),
            "genreCount" => $genres->count([]),
            "actorCount" => $actors->count([]),
            "directorCount" => $directors->count([]),
        ]);
    }
}
