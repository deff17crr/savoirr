<?php

namespace App\Repository;

use App\DTO\MemberContactData;
use App\Entity\Member;
use App\Entity\MemberContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class MemberContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MemberContact::class);
    }

    public function save(MemberContact $memberContact): MemberContact
    {
        $this->getEntityManager()->persist($memberContact);
        $this->getEntityManager()->flush();

        return $memberContact;
    }

    public function createContact(Member $member, MemberContactData $memberContactData): MemberContact
    {
        $memberContact = new MemberContact();
        $memberContact->setMember($member);
        $memberContact->setCity($memberContactData->city);
        $memberContact->setAddress($memberContactData->address);
        $memberContact->setPhoneNumber($memberContactData->phoneNumber);

        return $this->save($memberContact);
    }
}