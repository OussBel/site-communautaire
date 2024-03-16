<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom d'utilisateur",
                'attr' => [
                    'placeholder' => "Nom d'utilisateur",
                ],
                'constraints' => [
                    new NotBlank(message: "Le nom d'utilisateur obligatoire"),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Il faut au moins {{ limit }} caractères',
                        'max' => 100,
                        'maxMessage' => 'Il ne faut pas dépasser {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Email',
                ],
                'constraints' => [
                    new NotBlank(message: "L'émail est obligatoire"),
                    new Email(message: "L'émail {{ value }} n'est pas valide")
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'constraints' => [
                    new NotBlank(message: "Le mot de passe est obligatoire"),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Il faut au moins {{ limit }} caractères'
                    ]),
                ]
            ])
            ->add('image', FileType::class, [
                'constraints' => [
                    new Image([
                        'maxSize' => '10M',
                        'maxSizeMessage' => 'La taille du fichier ne doit pas dépasser 2M',
                        'mimeTypes' => ['image/*'],
                        'mimeTypesMessage' => 'Veuillez ajouter un type d\'image valide'
                    ]),
                    new NotBlank(message: 'L\'image est obligatoire')
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "S'inscrire"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
