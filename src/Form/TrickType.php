<?php

namespace App\Form;

use App\Entity\Groupe;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Nom',
                ],
            ] )
          //  ->add('illustrations', CollectionType::class, [
          //      'entry_type' => IllustrationsType::class,
          //      'entry_options' => [
          //          'label' => false
          //      ],
          //      'allow_add' => true,
          //      'allow_delete' => true,
          //      'by_reference' => false,
          //      'required' => false,
         //       'label' => false,
         //   ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Description',
                ],
            ])
            ->add('groupe', EntityType::class,  [
                'label' => 'Groupe',
                'class' => Groupe::class,
                'choice_label' => 'name',
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Soumettre",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
