<?php
namespace App\Controller;

use App\Entity\Review;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Класс только задаёт префикс, без имени
#[Route("/profile")]
class ProfileController extends AbstractController
{
    // Здесь мы объявляем имя маршрута
    #[Route("", name: "app_profile", methods: ["GET", "POST"])]
    public function index(
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute("app_login");
        }

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash("success", "Profile updated.");

            return $this->redirectToRoute("app_profile");
        }

        $reviews = $em
            ->getRepository(Review::class)
            ->findBy(["author" => $user]);

        return $this->render("profile/index.html.twig", [
            "form" => $form->createView(),
            "reviews" => $reviews,
        ]);
    }
}
