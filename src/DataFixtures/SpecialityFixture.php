<?php

namespace App\DataFixtures;

use App\Entity\Speciality;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SpecialityFixture extends Fixture
{
    public const array SPECIALITIES = [
        'Cardiology',
        'Dermatology',
        'Neurology',
        'Pediatrics',
        'Orthopedics',
        'Psychiatry',
        'General Medicine',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::SPECIALITIES as $index => $title) {
            $speciality = new Speciality();
            $speciality->setTitle($title);

            $manager->persist($speciality);

            // Reference for DoctorFixture
            $this->addReference('speciality_' . $index, $speciality);
        }

        $manager->flush();
    }
}
