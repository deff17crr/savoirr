<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

trait Creatable
{
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $createdAt = null;

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new DateTime('now');
    }
}