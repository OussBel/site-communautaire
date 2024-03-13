<?php

namespace App\Form;

use App\Entity\Illustrations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;

class IllustrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, [
                'label' => 'Ajouter une image',
                'mapped' => true,
                'required' => true,
                'constraints' => [
                    new NotNull(['message' => 'Veuillez télécharger une image.']),
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/*'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide',
                        'maxSizeMessage' => 'La taille du fichier est grande, veuillez ajouter une image <= 2M',
                    ]),
                ],
                'attr' => [
                    'accept' => 'image/*',
                ]
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Illustrations::class,
        ]);
    }
}
