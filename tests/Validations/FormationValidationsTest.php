<?php

namespace App\Tests\Validations;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Classe de tests pour valider les contraintes de l'entité Formation.
 *
 * Cette classe vérifie :
 * - La validité des dates de publication selon les contraintes définies.
 * - Le bon fonctionnement des validations Symfony.
 */
class FormationValidationTest extends KernelTestCase
{
    /**
     * Crée une nouvelle instance de la classe Formation avec des données par défaut.
     *
     * @return Formation
     */
    public function getFormation(): Formation
    {
        return (new Formation())
            ->setTitle("Test") // Titre valide par défaut
            ->setVideoId("123"); // ID vidéo valide par défaut
    }

    /**
     * Vérifie le nombre d'erreurs de validation pour une entité Formation donnée.
     *
     * @param Formation $formation L'entité Formation à valider.
     * @param int $nbErreursAttendues Le nombre d'erreurs attendu.
     */
    public function assertErrors(Formation $formation, int $nbErreursAttendues): void
    {
        self::bootKernel(); // Lance le noyau Symfony pour les tests
        $validator = self::getContainer()->get(ValidatorInterface::class); // Récupère le service de validation
        $errors = $validator->validate($formation); // Valide l'entité Formation

        // Vérifie que le nombre d'erreurs correspond à celui attendu
        $this->assertCount(
            $nbErreursAttendues,
            $errors,
            "Le nombre d'erreurs de validation ne correspond pas à celui attendu."
        );
    }

    /**
     * Teste une date de publication valide.
     *
     * Vérifie que l'entité Formation est valide lorsque la date de publication
     * respecte les contraintes (par exemple, date future ou valide).
     */
    public function testValidDateFormation(): void
    {
        // Date valide dans le futur
        $formation = $this->getFormation()->setPublishedAt(new \DateTime("2025-01-04 17:00:12"));

        // Aucun message d'erreur attendu
        $this->assertErrors($formation, 0);
    }

    /**
     * Teste une date de publication non valide.
     *
     * Vérifie que l'entité Formation est invalide lorsque la date de publication
     * ne respecte pas les contraintes (par exemple, date passée).
     */
    public function testNonValidDateFormation(): void
    {
        // Date invalide dans le passé
        $formation = $this->getFormation()->setPublishedAt(new \DateTime("2024-01-04 17:00:12"));

        // Un message d'erreur attendu
        $this->assertErrors($formation, 1);
    }
}
