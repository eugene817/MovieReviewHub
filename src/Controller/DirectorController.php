<?php

namespace App\Controller;

use App\Entity\Director;
use App\Form\DirectorForm;
use App\Repository\DirectorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/director')]
final class DirectorController extends AbstractController
{
    #[Route(name: 'app_director_index', methods: ['GET'])]
    public function index(DirectorRepository $directorRepository): Response
    {
        return $this->render('director/index.html.twig', [
            'directors' => $directorRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_director_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $director = new Director();
        $form = $this->createForm(DirectorForm::class, $director);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($director);
            $entityManager->flush();

            return $this->redirectToRoute('app_director_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('director/new.html.twig', [
            'director' => $director,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_director_show', methods: ['GET'])]
    public function show(Director $director): Response
    {
        return $this->render('director/show.html.twig', [
            'director' => $director,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_director_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Director $director, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DirectorForm::class, $director);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_director_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('director/edit.html.twig', [
            'director' => $director,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_director_delete', methods: ['POST'])]
    public function delete(Request $request, Director $director, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$director->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($director);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_director_index', [], Response::HTTP_SEE_OTHER);
    }
}
