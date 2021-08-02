<?php

namespace App\DataFixtures;

use App\Entity\Promotion;
use App\Service\Timetable\PromotionService;
use App\Service\Timetable\TimetableService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PromotionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach (PromotionService::PROMOTIONS_FRIENDLY_ABBR as $abbr => $name) {
            $manager->persist(
                (new Promotion())
                    ->setLink(sprintf(TimetableService::BASE_URL, $abbr))
                    ->setFile(sprintf(TimetableService::BASE_URL, $abbr))
                    ->setCode($abbr)
                    ->setName($name)
                    ->setCreatedAt(new \DateTimeImmutable())
            );
        }

        $manager->flush();
    }
}
