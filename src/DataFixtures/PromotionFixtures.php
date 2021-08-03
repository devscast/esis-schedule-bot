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
        foreach (PromotionService::PROMOTIONS_FRIENDLY_ABBR as $code => $name) {
            $manager->persist(
                (new Promotion())
                    ->setLink(str_ireplace(' ', '%20', sprintf(TimetableService::BASE_URL, $code)))
                    ->setFile(str_ireplace(' ', '%20', sprintf(TimetableService::BASE_URL, $code)))
                    ->setCode($code)
                    ->setName($name)
                    ->setCreatedAt(new \DateTimeImmutable())
            );
        }

        $manager->flush();
    }
}
