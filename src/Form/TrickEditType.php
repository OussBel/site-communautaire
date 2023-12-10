<?php

namespace App\Form;

use App\Entity\Groupe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr'  => [
                    'placeholder' => 'Nom',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Déscription',
                'attr'  => [
                    'placeholder' => 'Déscription',
                ],
            ])
            ->add('groupe', EntityType::class, [ // Adding the group select input
                'label' => 'Groupe',
                'class' => Groupe::class, // Assuming Group is your entity class
                'choice_label' => 'name', // Display blank option
                'placeholder' => '', // Optional placeholder text
            ])
            ->add('illustration', FileType::class, [
                'label' => 'Illustration',
                'required' => false,
                'multiple' => true,
                'attr' => [
                    'accept' => 'image/*',
                ],
            ])
            ->add('video', UrlType::class, [
                'label' => 'Lien de la vidéo',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Soummettre",
            ])
        ;

        ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
