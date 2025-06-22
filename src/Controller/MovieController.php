<?php
namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Review;
use App\Form\MovieType;
use App\Form\ReviewType;
use App\Repository\MovieRepository;
use App\Service\OmdbClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/movie")]
final class MovieController extends AbstractController
{
    #[Route("/", name: "app_movie_index", methods: ["GET"])]
    public function index(MovieRepository $movieRepository): Response
    {
        return $this->render("movie/index.html.twig", [
            "movies" => $movieRepository->findAll(),
        ]);
    }

    #[Route("/new", name: "app_movie_new", methods: ["GET", "POST"])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        OmdbClient $omdbClient
    ): Response {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $omdbClient->fetchByTitle($movie->getTitle());

            $movie
                ->setApiId($data["imdbID"] ?? null)
                ->setDescription($data["Plot"] ?? null)
                ->setReleaseDate(
                    !empty($data["Released"])
                        ? new \DateTime($data["Released"])
                        : null
                )
                ->setPoster($data["Poster"] ?? null)
                ->setRating($data["imdbRating"] ?? null);

            $entityManager->persist($movie);
            $entityManager->flush();

            $this->addFlash("success", "Film succesfullyy added.");
            return $this->redirectToRoute("app_movie_show", [
                "id" => $movie->getId(),
            ]);
        }

        return $this->render("movie/new.html.twig", [
            "form" => $form->createView(),
        ]);
    }

    #[Route("/{id}", name: "app_movie_show", methods: ["GET", "POST"])]
    public function show(
        Movie $movie,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $user = $this->getUser();
        $formView = null;

        if ($user) {
            $review = (new Review())
                ->setMovie($movie)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setAuthor($user);

            $form = $this->createForm(ReviewType::class, $review);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($review);
                $em->flush();
                $this->addFlash("success", "Your review has been added.");

                return $this->redirectToRoute("app_movie_show", [
                    "id" => $movie->getId(),
                ]);
            }

            $formView = $form->createView();
        }

        return $this->render("movie/show.html.twig", [
            "movie" => $movie,
            "reviews" => $movie->getReviews(),
            "form" => $formView,
        ]);
    }

    #[Route("/{id}/edit", name: "app_movie_edit", methods: ["GET", "POST"])]
    public function edit(
        Request $request,
        Movie $movie,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash("success", "Changes saved.");

            return $this->redirectToRoute("app_movie_index");
        }

        return $this->render("movie/edit.html.twig", [
            "movie" => $movie,
            "form" => $form->createView(),
        ]);
    }

    #[Route("/{id}", name: "app_movie_delete", methods: ["POST"])]
    public function delete(
        Request $request,
        Movie $movie,
        EntityManagerInterface $entityManager
    ): Response {
        $token = $request->request->get("_token");
        if ($this->isCsrfTokenValid("delete" . $movie->getId(), $token)) {
            $entityManager->remove($movie);
            $entityManager->flush();
            $this->addFlash("success", "Film deleted.");
        }

        return $this->redirectToRoute("app_movie_index");
    }
}
