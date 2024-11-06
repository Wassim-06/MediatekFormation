<?php

namespace App\Tests;

use App\Entity\Formation;
use PHPUnit\Framework\TestCase;

class FormationTest extends TestCase
{

    public function testGetPublishedAtString()
    {
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTime("2022-01-04 17:00:12"));
        $this->assertEquals("04/01/2022", $formation->getPublishedAtString());
    }
}
