<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieForm;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/movie')]
final class MovieController extends AbstractController
{
  #[Route(name: 'app_movie_index', methods: ['GET'])]
  public function index(MovieRepository $movieRepository): Response
  {
    return $this->render('movie/index.html.twig', [
      'movies' => $movieRepository->findAll(),
    ]);
  }

  #[Route('/new', name: 'app_movie_new', methods: ['GET', 'POST'])]
  public function new(
    Request $request,
    EntityManagerInterface $em,
    MovieApiService $api
  ): Response {
    $movie = new Movie();
    $form = $this->createForm(MovieType::class, $movie);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $apiId = $movie->getApiId();

      try {
        $data = $api->fetchById($apiId);
        $movie
          ->setTitle($data['Title'])
          ->setPoster($data['Poster'])
          ->setDescription($data['Plot'])
          ->setReleaseDate(new \DateTime($data['Released']))
          ->setRating(floatval($data['imdbRating']));
      } catch (\Throwable $e) {
        $this->addFlash('danger', 'error in API: ' . $e->getMessage());
        return $this->redirectToRoute('app_movie_new');
      }

      $em->persist($movie);
      $em->flush();

      $this->addFlash('success', 'Film added!');
      return $this->redirectToRoute('app_movie_show', ['id' => $movie->getId()]);
    }

    return $this->render('movie/new.html.twig', [
      'form' => $form->createView(),
    ]);
  }

  #[Route('/{id}', name: 'app_movie_show', methods: ['GET'])]
  public function show(Movie $movie): Response
  {
    return $this->render('movie/show.html.twig', [
      'movie' => $movie,
    ]);
  }

  #[Route('/{id}/edit', name: 'app_movie_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Movie $movie, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(MovieForm::class, $movie);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('app_movie_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('movie/edit.html.twig', [
      'movie' => $movie,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'app_movie_delete', methods: ['POST'])]
  public function delete(Request $request, Movie $movie, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete' . $movie->getId(), $request->getPayload()->getString('_token'))) {
      $entityManager->remove($movie);
      $entityManager->flush();
    }

    return $this->redirectToRoute('app_movie_index', [], Response::HTTP_SEE_OTHER);
  }
}
