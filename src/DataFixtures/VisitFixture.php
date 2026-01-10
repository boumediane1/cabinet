<?php

namespace App\DataFixtures;

use App\Entity\Doctor;
use App\Entity\Patient;
use App\Entity\Visit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class VisitFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 50; $i++) {
            $visit = new Visit();

            $visit->setTime(
                $faker->dateTimeBetween('-6 months', '+1 month')
            );

            $visit->setPatient(
                $this->getReference('patient_' . $faker->numberBetween(1, 20), Patient::class)
            );

            $visit->setDoctor(
                $this->getReference('doctor_' . $faker->numberBetween(1, 10), Doctor::class)
            );

            $manager->persist($visit);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PatientFixture::class,
            DoctorFixture::class,
        ];
    }
}
