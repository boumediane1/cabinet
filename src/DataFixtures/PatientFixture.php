<?php

namespace App\DataFixtures;

use App\Entity\Patient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PatientFixture extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('en_US');

        for ($i = 1; $i <= 20; $i++) {
            $patient = new Patient();

            $patient->setUsername($faker->unique()->userName());
            $patient->setName($faker->name());
            $patient->setRoles(['ROLE_PATIENT']);

            $hashedPassword = $this->passwordHasher->hashPassword(
                $patient,
                'password123'
            );
            $patient->setPassword($hashedPassword);

            $patient->setBirthDate(
                $faker->dateTimeBetween('-90 years', '-1 year')
            );

            $patient->setGender(
                $faker->randomElement(['male', 'female'])
            );

            $patient->setAddress(
                $faker->optional()->address()
            );

            $manager->persist($patient);

            $this->addReference('patient_' . $i, $patient);
        }

        $manager->flush();
    }
}
