<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PlaylistsControllerTest extends WebTestCase
{
    public function testAccesPage()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testContenuPage()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $this->assertSelectorTextContains('h1', 'Playlists');
    }

    public function testLinkDESCNamePlaylists()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $client->clickLink('⏷');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/playlists/tri/name/DESC', $uri);
    }

    public function testLinkASCNamePlaylists()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $client->clickLink('⏶');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/playlists/tri/name/ASC', $uri);
    }

    public function testLinkASCNbreFormaPlaylists()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');
        $links = $crawler->selectLink('⏶');
        $link = $links->eq(1)->link();
        $client->click($link);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/playlists/tri/nbrdeformation/ASC', $uri);
    }

    public function testLinkDESCNbreFormaPlaylists()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');
        $links = $crawler->selectLink('⏷');
        $link = $links->eq(1)->link();
        $client->click($link);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/playlists/tri/nbrdeformation/DESC', $uri);
    }

    public function testFiltreNamePlaylists()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Test'
        ]);
        $this->assertCount(1, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Testadd');
    }

    public function testFiltreCategoriesPlaylist()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');
        $form = $crawler->filter('form')->eq(1)->form([
            'recherche' => '1' // Remplir le champ 'recherche' pour le deuxième formulaire
        ]);
        $crawler = $client->submit($form);
        $this->assertCount(2, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Eclipse et Java');
    }
}
