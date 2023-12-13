<?php

namespace App\Repository;

use App\Entity\PoliticalGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class PoliticalGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PoliticalGroup::class);
    }

    public function save(PoliticalGroup $politicalGroup): PoliticalGroup
    {
        $this->getEntityManager()->persist($politicalGroup);
        $this->getEntityManager()->flush();

        return $politicalGroup;
    }

    public function getPoliticalGroupsIndexedByName(): array
    {
        $indexedPoliticalGroups = [];
        /** @var PoliticalGroup $country */
        foreach ($this->findAll() as $politicalGroup) {
            $indexedPoliticalGroups[$politicalGroup->getName()] = $politicalGroup;
        }

        return $indexedPoliticalGroups;
    }

    public function create(string $name): PoliticalGroup
    {
        $group = new PoliticalGroup();
        $group->setName($name);

        return $this->save($group);
    }
}