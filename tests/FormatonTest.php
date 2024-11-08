<?php

namespace App\Tests;

use App\Entity\Formation;
use PHPUnit\Framework\TestCase;



/**
 * Class de test pour la class Formation
 * 
 * @author Wa2s
 */
class FormationTest extends TestCase
{

    public function testGetPublishedAtString()
    {
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTime("2022-01-04 17:00:12"));
        $this->assertEquals("04/01/2022", $formation->getPublishedAtString());
    }
}
