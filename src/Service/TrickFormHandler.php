<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickFormHandler
{
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly SluggerInterface       $slugger
    )
    {
    }

    public function handleForm($form, $currentUser): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {

            $trick = $form->getData();
            $slug = $this->slugger->slug(strtolower($trick->getName()));
            $trick->setSlug($slug);
            $trick->setUser($currentUser);
            $this->entityManager->persist($trick);
            $this->entityManager->flush();

            return true;
        }
        return false;
    }



}