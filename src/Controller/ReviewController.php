<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Review;
use App\Form\ReviewForm;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/review')]
final class ReviewController extends AbstractController
{
  #[Route(name: 'app_review_index', methods: ['GET'])]
  public function index(ReviewRepository $reviewRepository): Response
  {
    return $this->render('review/index.html.twig', [
      'reviews' => $reviewRepository->findAll(),
    ]);
  }

  #[Route('/new/{movie}', name: 'app_review_new', methods: ['GET', 'POST'])]
  public function new(Request $request, EntityManagerInterface $entityManager, Movie $movie): Response
  {
    $review = new Review();
    $review->setMovie($movie);
    $review->setAuthor($this->getUser());
    $review->setCreatedAt(new \DateTimeImmutable());

    $form = $this->createForm(ReviewForm::class, $review);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->persist($review);
      $entityManager->flush();

      return $this->redirectToRoute('app_review_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('review/new.html.twig', [
      'review' => $review,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'app_review_show', methods: ['GET'])]
  public function show(Review $review): Response
  {
    return $this->render('review/show.html.twig', [
      'review' => $review,
    ]);
  }

  #[Route('/{id}/edit', name: 'app_review_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Review $review, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(ReviewForm::class, $review);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      return $this->redirectToRoute('app_review_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('review/edit.html.twig', [
      'review' => $review,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'app_review_delete', methods: ['POST'])]
  public function delete(Request $request, Review $review, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete' . $review->getId(), $request->getPayload()->getString('_token'))) {
      $entityManager->remove($review);
      $entityManager->flush();
    }

    return $this->redirectToRoute('app_review_index', [], Response::HTTP_SEE_OTHER);
  }
}
