<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Classe de tests pour le contrôleur des formations.
 *
 * Cette classe teste les fonctionnalités suivantes :
 * - Accès à la page des formations.
 * - Présence du contenu attendu.
 * - Fonctionnement des liens de tri.
 * - Fonctionnement des filtres.
 * - Navigation vers une formation spécifique.
 */
class FormationsControllerTest extends WebTestCase
{
    /**
     * Teste si la page des formations est accessible.
     */
    public function testAccesPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * Teste si le titre "Formations" est présent sur la page.
     */
    public function testContenuPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $this->assertSelectorTextContains('h1', 'Formations');
    }

    /**
     * Teste le tri DESC sur le champ "title".
     */
    public function testLinkDESCTitleFormations(): void
    {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $client->clickLink('⏷');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/tri/title/DESC', $uri);
    }

    /**
     * Teste le tri ASC sur le champ "title".
     */
    public function testLinkASCTitleFormations(): void
    {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $client->clickLink('⏶');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/tri/title/ASC', $uri);
    }

    /**
     * Teste le tri ASC sur le champ "name" de la table "playlist".
     */
    public function testLinkASCPlaylistFormations(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $links = $crawler->selectLink('⏶');
        $link = $links->eq(1)->link();
        $client->click($link);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/tri/name/ASC/playlist', $uri);
    }

    /**
     * Teste le tri DESC sur le champ "name" de la table "playlist".
     */
    public function testLinkDESCPlaylistFormations(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $links = $crawler->selectLink('⏷');
        $link = $links->eq(1)->link();
        $client->click($link);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/tri/name/DESC/playlist', $uri);
    }

    /**
     * Teste le tri DESC sur le champ "publishedAt".
     */
    public function testLinkDESCDateFormations(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $links = $crawler->selectLink('⏷');
        $link = $links->eq(2)->link();
        $client->click($link);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/tri/publishedAt/DESC', $uri);
    }

    /**
     * Teste le tri ASC sur le champ "publishedAt".
     */
    public function testLinkASCDateFormations(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $links = $crawler->selectLink('⏶');
        $link = $links->eq(2)->link();
        $client->click($link);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/tri/publishedAt/ASC', $uri);
    }

    /**
     * Teste l'accès à une formation spécifique via un lien.
     */
    public function testLinkFormation(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $links = $crawler->selectLink('formation img');
        $link = $links->eq(2)->link();
        $client->click($link);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/formation/16', $uri);
    }

    /**
     * Teste le filtre par nom dans les formations.
     */
    public function testFiltreNameFormations(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'UML'
        ]);
        $this->assertCount(1, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'UML : Diagramme de classes');
    }

    /**
     * Teste le filtre par nom dans les playlists associées.
     */
    public function testFiltrePlaylistFormations(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $form = $crawler->filter('form')->eq(1)->form([
            'recherche' => 'Visual'
        ]);
        $crawler = $client->submit($form);
        $this->assertCount(1, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'C# : Lier List et ListBox');
    }

    /**
     * Teste le filtre par catégories associées.
     */
    public function testFiltreCategoriesFormations(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $form = $crawler->filter('form')->eq(2)->form([
            'recherche' => '1'
        ]);
        $crawler = $client->submit($form);
        $this->assertCount(2, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Eclipse n°8 : Déploiementsss');
    }
}
