<?php

namespace App\Form;

use App\Entity\Bac;
use App\Entity\Member;
use App\Entity\Vinyl;
use App\Repository\VinylRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BacType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $bac = $options['data'] ?? null;
        $member = $bac->getOwner();

        $builder
            ->add('public', null, ['label' => 'Bac public (visible par tous)'])
            ->add('name', null, ['label' => 'Nom'])
            ->add('description', null, ['label' => 'Description'])
            ->add('vinyls', EntityType::class, [
                'label' => 'Vinyles du bac',
                'class' => Vinyl::class,
                'multiple' => true,
                'required' => false,
                'expanded' => true,
                'by_reference' => false,
                'query_builder' => function (VinylRepository $er) use ($member) {
                return $er->createQueryBuilder('o')
                    ->leftJoin('o.library', 'i')
                    ->leftJoin('i.member', 'm')
                    ->andWhere('m.id = :memberId')
                    ->setParameter('memberId', $member->getId())
                    ;
                }
            ])
            ->add('owner', EntityType::class, [
                'label' => 'Propriétaire',
                'class' => Member::class,
                'disabled' => true,
                'choice_label' => 'username',
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
