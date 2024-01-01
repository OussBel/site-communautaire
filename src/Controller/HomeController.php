<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use App\Service\FormHandler;
use App\Service\TrickFormHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class HomeController
 *
 */
class HomeController extends AbstractController
{

    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly SluggerInterface       $slugger,
    )
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(TrickRepository $trickRepository): Response
    {
        $tricks = $trickRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('home/index.html.twig', ['tricks' => $tricks]);
    }

    #[Route('/figure/{slug}', name: 'app_trick')]
    public function show(Trick $trick, Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        return $this->render('home/show.html.twig', ['trick' => $trick, 'form' => $form]);
    }

    #[Route('/compte/ajouter-une-figure', name: 'app_trick_add')]
    public function add(Request $request, TrickFormHandler $trickFormHandler): Response
    {

        $this->denyAccessUnlessGranted('ROLE_USER');

        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        $currentUser = $this->getUser();

        if ($trickFormHandler->handleForm($form, $currentUser)) {

            $this->addFlash('success', 'La figure a été ajoutée');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/form.html.twig', ['form' => $form]);
    }


    #[Route('/compte/modifier-une-figure/{id<\d+>}', name: 'app_trick_edit')]
    public function edit(Trick $trick, Request $request, TrickFormHandler $trickFormHandler): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($this->getUser() !== $trick->getUser()) return $this->redirectToRoute('app_home');

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        $currentUser = $this->getUser();

        if ($trickFormHandler->handleForm($form, $currentUser)) {

            $this->addFlash('success', 'La figure a été mise à jour');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/form.html.twig', ['form' => $form]);

    }

    #[Route('/compte/supprimer-une-figure/{id<\d+>}', name: 'app_trick_delete')]
    public function delete(Request $request, Trick $trick): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $this->entityManager->remove($trick);
        $this->entityManager->flush();

        $this->addFlash('success', 'La figure a été supprimée');

        return $this->redirectToRoute('app_home');
    }


}




