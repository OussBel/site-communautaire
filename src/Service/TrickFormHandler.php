<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickFormHandler
{
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly SluggerInterface       $slugger,
                                private readonly FileUploader           $fileUploader
    )
    {
    }

    public function handleForm($form, $currentUser): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {

            $trick = $form->getData();

            foreach ($trick->getIllustrations() as $illustration) {

                $image = $illustration->getFile();

                if($image instanceof UploadedFile ) {
                    $imageName = $this->fileUploader->upload($image);
                    $illustration->setName($imageName);
                    $illustration->setTrick($trick);
                }

            }

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