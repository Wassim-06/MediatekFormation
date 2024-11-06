<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class FormationsControllerTest extends WebTestCase
{
    public function testAccesPage()
    {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testContenuPage()
    {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $this->assertSelectorTextContains('h1', 'Formations');
    }

    public function testLinkDESCTitleFormations()
    {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $client->clickLink('⏷');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/tri/title/DESC', $uri);
    }

    public function testLinkASCTitleFormations()
    {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $client->clickLink('⏶');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/tri/title/ASC', $uri);
    }

    public function testLinkASCPlaylistFormations()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $links = $crawler->selectLink('⏶');
        $link = $links->eq(1)->link();
        $client->click($link);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/tri/name/ASC/playlist', $uri);
    }

    public function testLinkDESCPlaylistFormations()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $links = $crawler->selectLink('⏷');
        $link = $links->eq(1)->link();
        $client->click($link);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/tri/name/DESC/playlist', $uri);
    }

    public function testLinkDESCDateFormations()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $links = $crawler->selectLink('⏷');
        $link = $links->eq(2)->link();
        $client->click($link);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/tri/publishedAt/DESC', $uri);
    }

    public function testLinkASCDateFormations()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $links = $crawler->selectLink('⏶');
        $link = $links->eq(2)->link();
        $client->click($link);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/tri/publishedAt/ASC', $uri);
    }

    public function testLinkFormation()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $links = $crawler->selectLink('formation img');
        $link = $links->eq(2)->link();
        $client->click($link);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/formation/16', $uri);
    }

    public function testFiltreNameFormations()
    {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'UML'
        ]);
        $this->assertCount(1, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'UML : Diagramme de classes');
    }

    public function testFiltrePlaylistFormations()
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

    public function testFiltreCategoriesFormations()
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
