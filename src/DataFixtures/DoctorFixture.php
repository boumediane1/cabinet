<?php

namespace App\DataFixtures;

use App\Entity\Doctor;
use App\Entity\Speciality;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class DoctorFixture extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 10; $i++) {
            $doctor = new Doctor();

            $doctor->setUsername($faker->unique()->userName());
            $doctor->setName('Dr. ' . $faker->name());
            $doctor->setRoles(['ROLE_DOCTOR']);

            $doctor->setSpeciality(
                $this->getReference(
                    'speciality_' . $faker->numberBetween(0, 6),
                    Speciality::class
                )
            );

            $hashedPassword = $this->passwordHasher->hashPassword(
                $doctor,
                'password123'
            );
            $doctor->setPassword($hashedPassword);

            $manager->persist($doctor);

            // Reference for VisitFixture
            $this->addReference('doctor_' . $i, $doctor);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SpecialityFixture::class,
        ];
    }
}
