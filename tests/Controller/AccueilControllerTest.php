<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Classe de test pour le contrôleur d'accueil.
 *
 * Cette classe vérifie :
 * - L'accès à la page d'accueil.
 * - La présence de contenu spécifique sur la page.
 * - Le fonctionnement des liens principaux (Formations, Playlists, CGU).
 */
class AccueilControllerTest extends WebTestCase
{
    /**
     * Teste si la page d'accueil est accessible.
     *
     * @return void
     */
    public function testAccesPage(): void
    {
        $client = static::createClient();

        // Envoie une requête GET à la route racine "/"
        $client->request('GET', '/');

        // Vérifie que la réponse HTTP est 200 (OK)
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * Teste si le contenu attendu est présent sur la page d'accueil.
     *
     * Vérifie que le texte "Bienvenue sur le site de MediaTek86" est bien
     * contenu dans un élément HTML `<h3>`.
     *
     * @return void
     */
    public function testContenuPage(): void
    {
        $client = static::createClient();

        // Envoie une requête GET à la route racine "/"
        $client->request('GET', '/');

        // Vérifie que le texte attendu est présent dans un élément <h3>
        $this->assertSelectorTextContains(
            'h3',
            'Bienvenue sur le site de MediaTek86 consacré aux formations en ligne'
        );
    }

    /**
     * Teste le lien "Formations" dans la navbar.
     *
     * - Vérifie que le clic sur le lien redirige correctement.
     * - Vérifie que la route de destination est correcte.
     *
     * @return void
     */
    public function testLinkFormations(): void
    {
        $client = static::createClient();

        // Envoie une requête GET à la route racine "/"
        $client->request('GET', '/');

        // Clique sur le lien "Formations"
        $client->clickLink('Formations');

        // Vérifie que la réponse après le clic est 200 (OK)
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        // Vérifie que la route de destination est "/formations"
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations', $uri);
    }

    /**
     * Teste le lien "Playlists" dans la navbar.
     *
     * - Vérifie que le clic sur le lien redirige correctement.
     * - Vérifie que la route de destination est correcte.
     *
     * @return void
     */
    public function testLinkPlaylists(): void
    {
        $client = static::createClient();

        // Envoie une requête GET à la route racine "/"
        $client->request('GET', '/');

        // Clique sur le lien "Playlists"
        $client->clickLink('Playlists');

        // Vérifie que la réponse après le clic est 200 (OK)
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        // Vérifie que la route de destination est "/playlists"
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/playlists', $uri);
    }

    /**
     * Teste le lien "Conditions Générales d'Utilisation".
     *
     * - Vérifie que le clic sur le lien redirige correctement.
     * - Vérifie que la route de destination est correcte.
     *
     * @return void
     */
    public function testLinkCGU(): void
    {
        $client = static::createClient();

        // Envoie une requête GET à la route racine "/"
        $client->request('GET', '/');

        // Clique sur le lien "Conditions Générales d'Utilisation"
        $client->clickLink("Conditions Générales d'Utilisation");

        // Vérifie que la réponse après le clic est 200 (OK)
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        // Vérifie que la route de destination est "/cgu"
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/cgu', $uri);
    }
}
