<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Classe de tests pour le contrôleur des playlists.
 *
 * Cette classe teste :
 * - L'accès à la page des playlists.
 * - La présence du contenu attendu.
 * - Les fonctionnalités de tri par nom ou par nombre de formations.
 * - Les filtres appliqués par nom ou catégorie.
 */
class PlaylistsControllerTest extends WebTestCase
{
    /**
     * Teste si la page des playlists est accessible.
     */
    public function testAccesPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * Teste si le titre "Playlists" est présent sur la page.
     */
    public function testContenuPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $this->assertSelectorTextContains('h1', 'Playlists');
    }

    /**
     * Teste le tri DESC sur le champ "name".
     */
    public function testLinkDESCNamePlaylists(): void
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $client->clickLink('⏷');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/playlists/tri/name/DESC', $uri);
    }

    /**
     * Teste le tri ASC sur le champ "name".
     */
    public function testLinkASCNamePlaylists(): void
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $client->clickLink('⏶');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/playlists/tri/name/ASC', $uri);
    }

    /**
     * Teste le tri ASC sur le champ "nbrdeformation".
     */
    public function testLinkASCNbreFormaPlaylists(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');
        $links = $crawler->selectLink('⏶'); // Sélectionne tous les liens "⏶"
        $link = $links->eq(1)->link(); // Prend le deuxième lien "⏶" pour "nbrdeformation"
        $client->click($link);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/playlists/tri/nbrdeformation/ASC', $uri);
    }

    /**
     * Teste le tri DESC sur le champ "nbrdeformation".
     */
    public function testLinkDESCNbreFormaPlaylists(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');
        $links = $crawler->selectLink('⏷'); // Sélectionne tous les liens "⏷"
        $link = $links->eq(1)->link(); // Prend le deuxième lien "⏷" pour "nbrdeformation"
        $client->click($link);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/playlists/tri/nbrdeformation/DESC', $uri);
    }

    /**
     * Teste le filtre par nom dans les playlists.
     */
    public function testFiltreNamePlaylists(): void
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');

        // Soumet le formulaire de filtre par nom avec "Test"
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Test'
        ]);

        // Vérifie que le nombre de résultats est de 1
        $this->assertCount(1, $crawler->filter('h5'));

        // Vérifie que le texte "Testadd" est présent dans un élément <h5>
        $this->assertSelectorTextContains('h5', 'Testadd');
    }

    /**
     * Teste le filtre par catégorie dans les playlists.
     */
    public function testFiltreCategoriesPlaylist(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');

        // Sélectionne le deuxième formulaire (filtre par catégorie)
        $form = $crawler->filter('form')->eq(1)->form([
            'recherche' => '1' // Soumet "1" comme valeur pour la recherche
        ]);

        // Soumet le formulaire
        $crawler = $client->submit($form);

        // Vérifie que le nombre de résultats est de 2
        $this->assertCount(2, $crawler->filter('h5'));

        // Vérifie que le texte "Eclipse et Java" est présent dans un élément <h5>
        $this->assertSelectorTextContains('h5', 'Eclipse et Java');
    }
}
