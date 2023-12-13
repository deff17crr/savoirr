<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Entity\Trait\Creatable;
use App\Repository\MemberContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MemberContactRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['contact:item', 'member:item']),
    ],
)]
class MemberContact
{
    use Creatable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(groups: ['contact:item', 'member:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(groups: ['contact:item', 'member:item'])]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Ignore]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $phoneNumber = null;

    #[ORM\ManyToOne(targetEntity: Member::class, inversedBy: 'contacts')]
    #[Ignore]
    private Member $member;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getMember(): Member
    {
        return $this->member;
    }

    public function setMember(Member $member): void
    {
        $this->member = $member;
    }
}
