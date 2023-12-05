<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class RegisterController extends AbstractController
{

    #[Route('/inscription', name: 'app_register')]
    public function index(Request                     $request,
                          EntityManagerInterface      $entityManager,
                          UserPasswordHasherInterface $passwordHasher,
                          TokenGeneratorInterface     $tokenGeneratorInterface,
                          MailerService               $mailerService
    ): Response
    {

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $tokenRegistration = $tokenGeneratorInterface->generateToken();

            $user = $form->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $user->setTokenRegistration($tokenRegistration);

            $entityManager->persist($user);
            $entityManager->flush();

            $mailerService->send($user->getEmail(), "Confirmation du compte d'utilisateur",
                "registration_confirmation.html.twig",
                [
                    'user' => $user,
                    'token' => $tokenRegistration,
                    'lifeTimeToken' => $user->getTokenRegistrationLifeTime()->format('d-m-Y-H-i-s')
                ]
            );

            $this->addFlash('success', 'Votre compte a été crée,
             merci de confirmer votre email');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/index.html.twig', [
            'form' => $form,
        ]);

    }

    #[Route('/verify/{token}/{id<\d+>}', name: 'account_verify', methods: ['GET'])]
    public function verify(string $token, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($user->getTokenRegistration() !== $token) {
            throw new AccessDeniedException();
        }

        if ($user->getTokenRegistration() === null) {
            throw new AccessDeniedException();
        }

        if (new \DateTime('now') > $user->getTokenRegistrationLifeTime()) {
            throw new AccessDeniedException();
        }

        $user->setIsVerified(true);
        $user->setTokenRegistration(null);
        $entityManager->flush();

        $this->addFlash('success', 'Votre compte a bien été activé, vous pouvez maintenant vous connecter');

        return $this->redirectToRoute('app_login');
    }

}
