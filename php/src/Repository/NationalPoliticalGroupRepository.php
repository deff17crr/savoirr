<?php

namespace App\Repository;

use App\Entity\NationalPoliticalGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class NationalPoliticalGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NationalPoliticalGroup::class);
    }

    public function save(NationalPoliticalGroup $nationalPoliticalGroup): NationalPoliticalGroup
    {
        $this->getEntityManager()->persist($nationalPoliticalGroup);
        $this->getEntityManager()->flush();

        return $nationalPoliticalGroup;
    }

    public function getNationalPoliticalGroupsIndexedByName(): array
    {
        $indexedNationalPoliticalGroups = [];
        /** @var NationalPoliticalGroup $nationalPoliticalGroup */
        foreach ($this->findAll() as $nationalPoliticalGroup) {
            $indexedNationalPoliticalGroups[$nationalPoliticalGroup->getName()] = $nationalPoliticalGroup;
        }

        return $indexedNationalPoliticalGroups;
    }

    public function create(string $name): NationalPoliticalGroup
    {
        $group = new NationalPoliticalGroup();
        $group->setName($name);

        return $this->save($group);
    }
}