<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Illustrations;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Kernel;
use App\Repository\CommentRepository;
use App\Repository\IllustrationsRepository;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
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
            'page' => $page,
            'firstIllustration' => $trick->getFirstIllustration()
        ]);
    }


    #[Route('/compte/ajouter-une-figure', name: 'app_trick_add')]
    public function add(Request $request): Response
    {

        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        $currentUser = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {

            $trick = $form->getData();

            $slugger = new AsciiSlugger();

            $slug = strtolower($slugger->slug($trick->getName()));
            $trick->setSlug($slug);
            $trick->setUser($currentUser);

            $this->entityManager->persist($trick);
            $this->entityManager->flush();

            $this->addFlash('success', message: 'La figure a été créée avec succès');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/form.html.twig', ['form' => $form]);
    }


    #[Route('/compte/modifier-une-figure/{id<\d+>}', name: 'app_trick_edit')]
    public function edit(Trick                   $trick,
                         Request                 $request,
                         IllustrationsRepository $illustrationsRepository
    ): Response
    {

        if ($this->getUser() !== $trick->getUser()) return $this->redirectToRoute('app_home');


        $uploadedIllustrations = $illustrationsRepository->illustrationsByTrickId($trick);

        $form = $this->createForm(TrickType::class, $trick);


        $form->handleRequest($request);

        $currentUser = $this->getUser();


        return $this->render('home/form.html.twig',
            [
                'form' => $form,
                'uploadedIllustrations' => $uploadedIllustrations
            ]);

    }

    #[Route('/compte/supprimer-une-figure/{id<\d+>}', name: 'app_trick_delete')]
    public function delete(Trick $trick, Kernel $kernel): Response
    {

        if ($this->getUser() !== $trick->getUser()) return $this->redirectToRoute('app_home');

        $illustrations = $trick->getIllustrations();

        $this->entityManager->remove($trick);
        $this->entityManager->flush();

        $projectDir = $kernel->getProjectDir();

        foreach ($illustrations as $illustration) {
            $imagePath = $projectDir . '/public/assets/illustrations/' . $illustration->getName();

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }


        $this->addFlash('success', 'La figure a été supprimée avec succès');

        return $this->redirectToRoute('app_home');
    }

    #[Route('/compte/supprimer-une-image/{id<\d+>}', name: 'app_illustration_delete')]
    public function deleteIllustration(Illustrations $illustrations, Kernel $kernel): Response
    {

        $trick = $illustrations->getTrick();
        $trickId = $trick->getId();

        $projectDir = $kernel->getProjectDir();
        $imagePath = $projectDir . '/public/assets/illustrations/' . $illustrations->getName();

        $trick->removeIllustration($illustrations);

        $this->entityManager->remove($illustrations);
        $this->entityManager->flush();

        if (!$this->entityManager->contains($illustrations)) {
            if (file_exists($imagePath)) {
                unlink($imagePath);
                $this->addFlash('success', "L'image a été supprimée avec succès");
            } else {
                $this->addFlash('warning', "La suppression de l'image a échoué");
            }

        } else {
            $this->addFlash('danger', "La suppression de l'image a échoué");
        }

        return $this->redirectToRoute('app_trick_edit', ['id' => $trickId]);
    }


}




