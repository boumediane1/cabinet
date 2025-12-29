<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient extends User
{
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTime $birth_date = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    private ?string $gender = null;

    public function getBirthDate(): ?DateTime
    {
        return $this->birth_date;
    }

    public function setBirthDate(DateTime $birth_date): static
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }
}
