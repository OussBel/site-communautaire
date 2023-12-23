<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TrickRepository;
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

    /**
     * HomeController constructor.
     * @param EntityManagerInterface $entityManager The entity manager to be injected.
     */
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly SluggerInterface       $slugger,
    )
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(TrickRepository $trickRepository): Response
    {
        $tricks = $trickRepository->findAll();

        return $this->render('home/index.html.twig', ['tricks' => $tricks]);
    }

    #[Route('/figure/{slug}', name: 'app_trick')]
    public function show(Trick $trick): Response
    {
        return $this->render('home/show.html.twig', ['trick' => $trick]);
    }


    /**
     * @throws \Exception
     */
    #[Route('/ajouter-une-figure', name: 'app_trick_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trick = new Trick();

        return $this->render('home/form.html.twig');
    }

}




