<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickFormHandler
{
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private readonly SluggerInterface       $slugger,
                                private readonly FileUploader           $fileUploader,
                                private readonly VideoIframeExtractor   $videoIframeExtractor,
    )
    {
    }

    public function handleForm($form, $currentUser): bool
    {

        $trick = $form->getData();


        foreach ($trick->getVideos() as $video) {
            $videoIframe = $video->getName();
            $extractedSrc = $this->videoIframeExtractor->extractSrc($videoIframe);

            if (!$extractedSrc) {
                $form->get('videos')
                    ->addError(new FormError('Lien video invalide, veuillez ajouter un lien de vidéo intégré'));
                return false;
            } else {
                $video->setName($extractedSrc);
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($trick->getIllustrations() as $illustration) {

                $image = $illustration->getFile();

                if ($image instanceof UploadedFile) {
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