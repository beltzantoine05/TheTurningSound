<?php

namespace App\Form;

use App\Entity\Bac;
use App\Entity\Library;
use App\Entity\Vinyl;
use App\Repository\BacRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
class VinylType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $vinyl = $options['data'] ?? null;
        $member = $vinyl->getLibrary()->getMember();

        $builder
            ->add('artist', null, ['label' => 'Artiste'])
            ->add('title', null, ['label' => 'Titre'])
            ->add('year', null, ['label' => 'Année'])
            ->add('type', null, ['label' => 'Type (33 tours, 45 tours…)'])
            ->add('label', null, ['label' => 'Label'])
            ->add('prodCountry', null, ['label' => 'Pays de production'])
            ->add('prodYear', null, ['label' => 'Année de production'])
            ->add('signed', null, ['label' => 'Signé'])
            ->add('description', null, ['label' => 'Description'])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Pochette',
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
                'image_uri' => true,
                'delete_label' => 'Supprimer la pochette',
                'download_label' => 'Télécharger',
            ])
            ->add('library', EntityType::class, [
                'label' => 'Bibliothèque',
                'class' => Library::class,
                'disabled' => true,
            ])
            ->add('bacs', EntityType::class, [
                'label' => 'Bacs',
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
