<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AccueilControllerTest extends WebTestCase
{
    public function testAccesPage()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testContenuPage()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertSelectorTextContains('h3', 'Bienvenue sur le site de MediaTek86 consacré aux formations en ligne');
    }

    public function testLinkFormations()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        //clic sur un lien (navbar Formations)
        $client->clickLink('Formations');
        //Recupération du résultat du clic
        $response = $client->getResponse();
        //Controle si le lien existe
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        //Récupére la route et vérifie si elle est correcte
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations', $uri);
    }

    public function testLinkPlaylists()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        //clic sur un lien (navbar Playlists)
        $client->clickLink('Playlists');
        //Recupération du résultat du clic
        $response = $client->getResponse();
        //Controle si le lien existe
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        //Récupére la route et vérifie si elle est correcte
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/playlists', $uri);
    }

    public function testLinkCGU()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        //clic sur un lien (Conditions Générales d'Utilisation)
        $client->clickLink("Conditions Générales d'Utilisation");
        //Recupération du résultat du clic
        $response = $client->getResponse();
        //Controle si le lien existe
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        //Récupére la route et vérifie si elle est correcte
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/cgu', $uri);
    }
}
