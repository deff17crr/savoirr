<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Symfony\Component\Serializer\Annotation\Ignore;

trait Creatable
{
    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Ignore]
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