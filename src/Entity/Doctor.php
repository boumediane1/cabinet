<?php


namespace App\Entity;

use App\Repository\DoctorRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: DoctorRepository::class)]
class Doctor extends User
{
    #[ManyToOne]
    #[JoinColumn(nullable: false)]
    private ?Speciality $speciality = null;

    public function getSpeciality(): ?Speciality
    {
        return $this->speciality;
    }

    public function setSpeciality(?Speciality $speciality): static
    {
        $this->speciality = $speciality;

        return $this;
    }
}
