<?php

namespace App\Service;

use App\Entity\Images;
use App\Entity\Trick;
use App\Kernel;
use App\Repository\ImagesRepository;
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

    public function addImage(FormInterface $form, Trick $trick, UserInterface $currentUser): void
    {
        $name = $trick->getName();
        $slug = strtolower($this->slugger->slug($name));
        $trick->setSlug($slug);
        $trick->setUser($currentUser);

        $images = $form->get('images')->getData();

        foreach ($images as $image) {
            $this->processImage($image, $trick);
        }

        $this->entityManager->persist($trick);
        $this->entityManager->flush();
    }

    private function processImage(Images $image, Trick $trick): void
    {
        $uploadedImage = $image->getFile();
        $fileName = $this->fileUploader->upload($uploadedImage);
        $image->setName($fileName);
        $image->setTrick($trick);

        $this->entityManager->persist($image);
    }

    public function editExistingImage(array $files, Trick $trick, ImagesRepository $imagesRepository): void
    {
        foreach ($files as $key => $value) {
            $imageId = str_replace('image_', '', $key);
            $image = $imagesRepository->find($imageId);

            $this->entityManager->remove($image);
            $this->removeImage($image);

            $fileName = $this->fileUploader->upload($value);

            $image = new Images();
            $image->setName($fileName);
            $image->setTrick($trick);

            $this->entityManager->persist($image);
        }

        $this->entityManager->flush();
    }

    public function removeImage(Images $image): void
    {
        $projectDir = $this->kernel->getProjectDir();

        $imagePath = $projectDir . '/public/assets/illustrations/' . $image->getName();

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
}