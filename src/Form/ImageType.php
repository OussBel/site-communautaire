<?php

namespace App\Form;

use App\Entity\Images;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, [
                'label' => false,
                'mapped' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '3M',
                        'maxSizeMessage' => "L'image ne doit pas etre > 3M",
                        'mimeTypes' => ['image/*'],
                        'mimeTypesMessage' => "Veuillez ajouter un type d'image valide",
                    ]),
                    new NotBlank(message: 'Veuillez ajouter une image'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Images::class,
        ]);
    }
}
