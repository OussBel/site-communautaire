<?php

namespace App\Service;

use App\Entity\Illustrations;
use App\Entity\Trick;
use App\Kernel;
use App\Repository\IllustrationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickService
{

    public function __construct(
       private readonly EntityManagerInterface $entityManager,
       private readonly SluggerInterface       $slugger,
       private readonly FileUploader           $fileUploader,
        private readonly Kernel                $kernel
    ) {

    }

    public function createTrick(FormInterface $form, Trick $trick, UserInterface $currentUser): void
    {
        $slug = strtolower($this->slugger->slug($trick->getName()));
        $trick->setSlug($slug);
        $trick->setUser($currentUser);

        $illustrations = $form->get('illustrations')->getData();

        foreach ($illustrations as $illustration) {
            $this->processIllustration($illustration, $trick);
        }

        $this->entityManager->persist($trick);
        $this->entityManager->flush();
    }

    public function editTrick(array $files, Trick $trick, IllustrationsRepository $illustrationsRepository): void
    {
        foreach ($files as $key => $value) {
            $imageId = str_replace('image_', '', $key);
            $image = $illustrationsRepository->find($imageId);

            $this->entityManager->remove($image);
            $this->removeImage($image);

            $fileName = $this->fileUploader->upload($value);

            $illustration = new Illustrations();
            $illustration->setName($fileName);
            $illustration->setTrick($trick);

            $this->entityManager->persist($illustration);
        }

        $this->entityManager->flush();
    }

    private function processIllustration(Illustrations $illustration, Trick $trick): void
    {
        $image = $illustration->getFile();
        $fileName = $this->fileUploader->upload($image);
        $illustration->setName($fileName);
        $illustration->setTrick($trick);

        $this->entityManager->persist($illustration);
    }

    public function removeImage(Illustrations $image): void
    {
        $projectDir = $this->kernel->getProjectDir();

        $imagePath = $projectDir . '/public/assets/illustrations/' . $image->getName();

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
}
