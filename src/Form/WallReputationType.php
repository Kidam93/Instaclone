<?php

namespace App\Form;

use App\Entity\Reputation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WallReputationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('likes')
            ->add('comments')
            // ->add('date')
            // ->add('users')
            // ->add('profils')
            // ->add('walls')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reputation::class,
        ]);
    }
}
