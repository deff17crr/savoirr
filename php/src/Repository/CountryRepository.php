<?php

namespace App\Repository;

use App\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class CountryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }

    public function save(Country $country): Country
    {
        $this->getEntityManager()->persist($country);
        $this->getEntityManager()->flush();

        return $country;
    }

    public function getCountriesIndexedByName(): array
    {
        $indexedCounties = [];
        /** @var Country $country */
        foreach ($this->findAll() as $country) {
            $indexedCounties[$country->getName()] = $country;
        }

        return $indexedCounties;
    }

    public function create(string $name): Country
    {
        $country = new Country();
        $country->setName($name);

        return $this->save($country);
    }
}