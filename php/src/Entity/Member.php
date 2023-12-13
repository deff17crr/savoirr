<?php

namespace App\Entity;

use App\Entity\Trait\Creatable;
use App\Repository\MemberRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Member
{
    use Creatable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $mepId = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $fullName = null;

    #[ORM\ManyToOne(targetEntity: Country::class, inversedBy: 'members')]
    private Country $country;

    #[ORM\ManyToOne(targetEntity: PoliticalGroup::class, inversedBy: 'members')]
    private PoliticalGroup $politicalGroup;

    #[ORM\ManyToOne(targetEntity: NationalPoliticalGroup::class, inversedBy: 'members')]
    private NationalPoliticalGroup $nationalPoliticalGroup;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMepId(): ?int
    {
        return $this->mepId;
    }

    public function setMepId(?int $mepId): void
    {
        $this->mepId = $mepId;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): void
    {
        $this->country = $country;
    }

    public function getPoliticalGroup(): PoliticalGroup
    {
        return $this->politicalGroup;
    }

    public function setPoliticalGroup(PoliticalGroup $politicalGroup): void
    {
        $this->politicalGroup = $politicalGroup;
    }

    public function getNationalPoliticalGroup(): NationalPoliticalGroup
    {
        return $this->nationalPoliticalGroup;
    }

    public function setNationalPoliticalGroup(NationalPoliticalGroup $nationalPoliticalGroup): void
    {
        $this->nationalPoliticalGroup = $nationalPoliticalGroup;
    }
}
