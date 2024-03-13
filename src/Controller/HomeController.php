<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Illustrations;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Repository\IllustrationsRepository;
use App\Repository\TrickRepository;
use App\Service\FileUploader;
use App\Service\TrickService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param FileUploader $fileUploader
     * @param TrickService $trickService
     */
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly FileUploader           $fileUploader,
                                private readonly TrickService           $trickService
    )
    {
    }

    /**
     * @param TrickRepository $trickRepository
     * @return Response
     */
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(TrickRepository $trickRepository): Response
    {
        $tricks = $trickRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('home/index.html.twig', ['tricks' => $tricks]);
    }


    /**
     * @param Trick $trick
     * @param Request $request
     * @param CommentRepository $commentRepository
     * @return Response
     */
    #[Route('/figure/{slug}', name: 'app_trick', methods: ['GET', 'POST'])]
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


    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/compte/ajouter-une-figure', name: 'app_trick_add', methods: ['GET', 'POST'])]
    public function add(Request $request): Response
    {

        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
        $currentUser = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $this->trickService->createTrick($form, $trick, $currentUser);

            $this->addFlash('success', message: 'La figure a été créée avec succès');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/form.html.twig', ['form' => $form]);
    }


    /**
     * @param Trick $trick
     * @param Request $request
     * @param IllustrationsRepository $illustrationsRepository
     * @return Response
     */
    #[Route('/compte/modifier-une-figure/{id<\d+>}', name: 'app_trick_edit', methods: ['GET', 'POST'])]
    public function edit(Trick                   $trick,
                         Request                 $request,
                         IllustrationsRepository $illustrationsRepository
    ): Response
    {

        if ($this->getUser() !== $trick->getUser()) return $this->redirectToRoute('app_home');

        $uploadedIllustrations = $illustrationsRepository->illustrationsByTrickId($trick);

        $currentUser = $this->getUser();

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $files = array_filter($request->files->all());

            unset($files['trick']);

            $this->trickService->createTrick($form, $trick, $currentUser);
            $this->trickService->editTrick($files, $trick, $illustrationsRepository);

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/form.html.twig',
            [
                'form' => $form,
                'uploadedIllustrations' => $uploadedIllustrations
            ]);

    }

    /**
     * @param Trick $trick
     * @return Response
     */
    #[Route('/compte/supprimer-une-figure/{id<\d+>}', name: 'app_trick_delete', methods: ['GET', 'DELETE'])]
    public function delete(Trick $trick): Response
    {
        if ($this->getUser() !== $trick->getUser()) return $this->redirectToRoute('app_home');

        $illustrations = $trick->getIllustrations();

        $this->entityManager->remove($trick);
        $this->entityManager->flush();

        foreach ($illustrations as $illustration) {
            $this->trickService->removeImage($illustration);
        }

        $this->addFlash('success', 'La figure a été supprimée avec succès');

        return $this->redirectToRoute('app_home');
    }

    /**
     * @param Illustrations $illustrations
     * @return Response
     */
    #[Route('/compte/supprimer-une-image/{id<\d+>}', name: 'app_illustration_delete', methods: ['GET', 'DELETE'])]
    public function deleteIllustration(Illustrations $illustrations): Response
    {

        $trick = $illustrations->getTrick();
        $trickId = $trick->getId();
        $trick->removeIllustration($illustrations);

        $this->entityManager->remove($illustrations);
        $this->entityManager->flush();

        $this->trickService->removeImage($illustrations);

        return $this->redirectToRoute('app_trick_edit', ['id' => $trickId]);
    }

}




