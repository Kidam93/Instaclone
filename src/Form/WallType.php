<?php

namespace App\Form;

use App\Entity\Wall;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class WallType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => "Vos fichiers"
                ]
            ])
            ->add('comment');
            // ->add('date')
            // ->add('userComment')
            // ->add('profilComment')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Wall::class,
        ]);
    }
}
