<?php

namespace App\DataFixtures;

use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Entity\Patient;
use App\Entity\Speciality;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppointmentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Adjust counts to your dataset
        for ($i = 1; $i <= 5; $i++) {
            $doctor = $this->getReference(
                'doctor_' . (($i % 3) + 1),
                Doctor::class
            );

            $patient = $this->getReference(
                'patient_' . (($i % 5) + 1),
                Patient::class
            );

            $appointment = new Appointment();
            $appointment
                ->setDoctor($doctor)
                ->setSpeciality($doctor->getSpeciality())
                ->setTime(
                    new DateTime()
                        ->modify('+' . rand(1, 30) . ' days')
                        ->setTime(rand(8, 16), 0)
                )
                ->setConfirmed((bool) rand(0, 1));

            $appointment->setPatient($patient); // if you keep void setter

            /** @var Speciality $speciality */
            $speciality = $doctor->getSpeciality();

            $appointment = new Appointment();
            $appointment
                ->setDoctor($doctor)
                ->setPatient($patient)
                ->setSpeciality($speciality)
                ->setTime(
                    new DateTime()
                        ->modify('+' . rand(1, 30) . ' days')
                        ->setTime(rand(8, 16), 0)
                )
                ->setConfirmed((bool) rand(0, 1));

            $manager->persist($appointment);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            SpecialityFixture::class,
            DoctorFixture::class,
            PatientFixture::class,
        ];
    }
}
