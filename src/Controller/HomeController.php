<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
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

    /**
     * @throws \Exception
     */
    #[Route('/figure/{slug}', name: 'app_trick')]
    public function show(Trick $trick, Request $request, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setAuthor($this->getUser());
            $comment->setTrick($trick);

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_trick', ['slug' => $trick->getSlug()]);
        }

        $page = $request->query->getInt('page', 1);

        $comments = $commentRepository->pagination($page, $trick->getSlug());

        return $this->render('home/show.html.twig', [
            'trick' => $trick,
            'form' => $form,
            'comments' => $comments,
            'page' => $page
        ]);
    }


    #[Route('/compte/ajouter-une-figure', name: 'app_trick_add')]
    public function add(Request $request, TrickFormHandler $trickFormHandler): Response
    {

        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        $currentUser = $this->getUser();

        if ($trickFormHandler->handleForm($form, $currentUser)) {

            $this->addFlash('success', 'La figure a été ajoutée avec succès');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/form.html.twig', ['form' => $form]);
    }


    #[Route('/compte/modifier-une-figure/{id<\d+>}', name: 'app_trick_edit')]
    public function edit(Trick $trick, Request $request, TrickFormHandler $trickFormHandler): Response
    {

        if ($this->getUser() !== $trick->getUser()) return $this->redirectToRoute('app_home');

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        $currentUser = $this->getUser();

        if ($trickFormHandler->handleForm($form, $currentUser)) {

            $this->addFlash('success', 'La figure a été mise à jour avec succès');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/form.html.twig', ['form' => $form]);

    }

    #[Route('/compte/supprimer-une-figure/{id<\d+>}', name: 'app_trick_delete')]
    public function delete(Request $request, Trick $trick): Response
    {

        if ($this->getUser() !== $trick->getUser()) return $this->redirectToRoute('app_home');

        $this->entityManager->remove($trick);
        $this->entityManager->flush();

        $this->addFlash('success', 'La figure a été supprimée avec succès');

        return $this->redirectToRoute('app_home');
    }


}




