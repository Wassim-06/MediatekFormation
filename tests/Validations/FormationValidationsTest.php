<?php

namespace App\Tests\Validations;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FormationValidationTest extends KernelTestCase
{
    public function getFormation(): Formation
    {
        return (new Formation())
            ->setTitle("Test")
            ->setVideoId("123");
    }

    public function assertErrors(Formation $formation, int $nbErreursAttendues)
    {
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount($nbErreursAttendues, $error);
    }

    public function testValidDateFormation()
    {
        $formation = $this->getFormation()->setPublishedAt(new \DateTime("2025-01-04 17:00:12"));
        $this->assertErrors($formation, 0);
    }

    public function testNonValidDateFormation()
    {
        $formation = $this->getFormation()->setPublishedAt(new \DateTime("2024-01-04 17:00:12"));
        $this->assertErrors($formation, 1);
    }
}
