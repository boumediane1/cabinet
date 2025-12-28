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

    public function getBirthDate(): ?DateTime
    {
        return $this->birth_date;
    }

    public function setBirthDate(DateTime $birth_date): static
    {
        $this->birth_date = $birth_date;

        return $this;
    }
}
