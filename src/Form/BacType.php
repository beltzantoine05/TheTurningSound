<?php

namespace App\Form;

use App\Entity\Bac;
use App\Entity\Member;
use App\Entity\Vinyl;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BacType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('public')
            ->add('name')
            ->add('description')
            ->add('vinyls', EntityType::class, [
                'class' => Vinyl::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('owner', EntityType::class, [
                'class' => Member::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bac::class,
        ]);
    }
}
