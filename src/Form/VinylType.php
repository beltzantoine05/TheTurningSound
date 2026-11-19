<?php

namespace App\Form;

use App\Entity\Bac;
use App\Entity\Library;
use App\Entity\Vinyl;
use App\Repository\BacRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VinylType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $vinyl = $options['data'] ?? null;
        $member = $vinyl->getLibrary()->getMember();

        $builder
            ->add('artist')
            ->add('title')
            ->add('year')
            ->add('type')
            ->add('label')
            ->add('prodCountry')
            ->add('prodYear')
            ->add('signed')
            ->add('description')
            ->add('library', EntityType::class, [
                'class' => Library::class,
                'disabled' => true,
            ])
            ->add('bacs', EntityType::class, [
                'class' => Bac::class,
                'choice_label' => 'name',
                'multiple' => true,
                'by_reference' => false,
                'expanded' => true,
                'required' => false,
                'query_builder' => function (BacRepository $er) use ($member) {
                    return $er->createQueryBuilder('o')
                        ->leftJoin('o.owner', 'i')
                        ->andWhere('i.id = :memberId')
                        ->setParameter('memberId', $member)
                        ;
                }

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vinyl::class,
        ]);
    }
}
