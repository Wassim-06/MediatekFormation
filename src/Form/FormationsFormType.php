<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Playlist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Formulaire pour gérer les entités Formation.
 *
 * Ce formulaire permet de saisir et de modifier les informations
 * liées à une formation, notamment :
 * - La date de publication.
 * - Le titre et la description.
 * - L'ID de la vidéo associée.
 * - La playlist et les catégories associées.
 */
class FormationsFormType extends AbstractType
{
    /**
     * Construit le formulaire pour l'entité Formation.
     *
     * @param FormBuilderInterface $builder Le constructeur de formulaire.
     * @param array $options Les options supplémentaires pour le formulaire.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ pour la date de publication
            ->add('publishedAt', null, [
                'widget' => 'choice',
                'required' => true,
                'label' => 'Date de publication',
                'constraints' => [
                    new Assert\GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date ne peut pas être antérieure à aujourd\'hui.',
                    ]),
                ],
            ])
            // Champ pour le titre de la formation
            ->add('title', null, [
                'required' => true,
                'label' => 'Titre',
            ])
            // Champ pour la description de la formation
            ->add('description', null, [
                'required' => false,
                'label' => 'Description',
            ])
            // Champ pour l'ID de la vidéo associée
            ->add('videoId', null, [
                'required' => true,
                'label' => 'ID de la vidéo',
            ])
            // Champ pour sélectionner la playlist associée
            ->add('playlist', EntityType::class, [
                'class' => Playlist::class,
                'choice_label' => 'id', // Affiche l'identifiant de la playlist comme option
                'required' => true,
                'label' => 'Playlist',
            ])
            // Champ pour sélectionner les catégories associées
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'id', // Affiche l'identifiant de la catégorie comme option
                'multiple' => true, // Permet de sélectionner plusieurs catégories
                'required' => false,
                'label' => 'Catégories',
            ]);
    }

    /**
     * Configure les options par défaut pour le formulaire.
     *
     * @param OptionsResolver $resolver Le résolveur d'options.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Associe le formulaire à l'entité Formation
            'data_class' => Formation::class,
        ]);
    }
}
