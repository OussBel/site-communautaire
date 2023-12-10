<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickEditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $tricks = $this->entityManager->getRepository(Trick::class)->findAll();

        return $this->render('home/index.html.twig', ['tricks' => $tricks]);
    }

    #[Route('/figure/{slug}', name: 'app_trick')]
    public function show($slug): Response
    {
        $trick = $this->entityManager->getRepository(Trick::class)->findOneBySlug($slug);

        return $this->render('home/show.html.twig', ['trick' => $trick]);
    }




}
