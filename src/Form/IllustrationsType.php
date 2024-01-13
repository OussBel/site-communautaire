<?php

namespace App\Form;

use App\Entity\Illustrations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class IllustrationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, [
                'label' => 'Joindre une image',
                'mapped' => true, // Set mapped to true
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/bmp',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un document de type image valide',
                    ])
                ],
                'attr' => [
                    'accept' => 'image/*',
                ]
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Illustrations::class,
        ]);
    }
}
