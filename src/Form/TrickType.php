<?php

namespace App\Form;

use App\Entity\Groupe;
use App\Entity\Illustrations;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom',
                ],
            ])
            ->add('illustrations', CollectionType::class, [
                'entry_type' => IllustrationType::class,
                'entry_options' => [
                    'label' => false
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' =>
                    function (Illustrations $image = null) {
                        return null === $image || empty($image->getName());
                    },

                'by_reference' => false,
                'required' => true,
                'label' => false,
                'prototype' => true,
            ])
            ->add('videos', CollectionType::class, [
                'entry_type' => VideoType::class,
                'entry_options' => [
                    'label' => false
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => true,
                'label' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Description',
                ],
            ])
            ->add('groupe', EntityType::class, [
                'label' => 'Groupe',
                'class' => Groupe::class,
                'choice_label' => 'name',
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Soumettre",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
