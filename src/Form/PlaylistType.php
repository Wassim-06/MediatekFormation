<?php

namespace App\Form;

use App\Entity\Playlist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire pour gérer les entités Playlist.
 *
 * Ce formulaire permet de saisir et de modifier les informations
 * liées à une playlist, notamment :
 * - Le nom de la playlist.
 * - La description de la playlist.
 * - Le nombre de formations associées.
 */
class PlaylistType extends AbstractType
{
    /**
     * Construit le formulaire pour l'entité Playlist.
     *
     * @param FormBuilderInterface $builder Le constructeur de formulaire.
     * @param array $options Les options supplémentaires pour le formulaire.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ pour le nom de la playlist
            ->add('name', null, [
                'required' => true, // Ce champ est obligatoire
                'label' => 'Nom de la playlist', // Ajout d'un label pour le champ
            ])
            // Champ pour la description de la playlist
            ->add('description', null, [
                'required' => false, // Ce champ est facultatif
                'label' => 'Description', // Ajout d'un label pour le champ
            ])
            // Champ pour le nombre de formations associées
            ->add('nbrdeformation', null, [
                'required' => true, // Ce champ est obligatoire
                'label' => 'Nombre de formations', // Ajout d'un label pour le champ
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
            // Associe le formulaire à l'entité Playlist
            'data_class' => Playlist::class,
        ]);
    }
}
