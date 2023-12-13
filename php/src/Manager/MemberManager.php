<?php

namespace App\Manager;

use App\DTO\MemberListData;
use App\Entity\Country;
use App\Entity\Member;
use App\Entity\NationalPoliticalGroup;
use App\Entity\PoliticalGroup;
use App\Repository\CountryRepository;
use App\Repository\MemberRepository;
use App\Repository\NationalPoliticalGroupRepository;
use App\Repository\PoliticalGroupRepository;

class MemberManager
{
    private array $indexedCountries = [];

    private array $indexedPoliticalGroups = [];

    private array $indexedNationalPoliticalGroups = [];

    public function __construct(
        private readonly MemberRepository $memberRepository,
        private readonly CountryRepository $countryRepository,
        private readonly PoliticalGroupRepository $politicalGroupRepository,
        private readonly NationalPoliticalGroupRepository $nationalPoliticalGroupRepository,
    ) {
    }

    public function save(Member $member): Member
    {
        return $this->memberRepository->save($member);
    }

    public function findByMepId(int $mepId): ?Member
    {
        return $this->memberRepository->findOneBy(['mepId' => $mepId]);
    }

    public function createMember(MemberListData $memberData): ?Member
    {
        $member = new Member();
        $member->setMepId((int) $memberData->id);
        $member->setFullName($memberData->fullName);
        $member->setCountry($this->getCountry($memberData->country));
        $member->setPoliticalGroup($this->getPoliticalGroup($memberData->politicalGroup));
        $member->setNationalPoliticalGroup($this->getNationalPoliticalGroup($memberData->nationalPoliticalGroup));

        return $this->memberRepository->save($member);
    }

    private function getCountry(string $countryName): Country
    {
        if (count($this->indexedCountries) === 0) {
            $this->indexedCountries = $this->countryRepository->getCountriesIndexedByName();
        }

        /* Create Country if it doesn't exist */
        if (!array_key_exists($countryName, $this->indexedCountries)) {
            $country = $this->countryRepository->create($countryName);
            $this->indexedCountries[$countryName] = $country;
        }

        return $this->indexedCountries[$countryName];
    }

    private function getPoliticalGroup(string $politicalGroupName): PoliticalGroup
    {
        if (count($this->indexedPoliticalGroups) === 0) {
            $this->indexedPoliticalGroups = $this->politicalGroupRepository->getPoliticalGroupsIndexedByName();
        }

        /* Create Political Group if it doesn't exist */
        if (!array_key_exists($politicalGroupName, $this->indexedPoliticalGroups)) {
            $country = $this->politicalGroupRepository->create($politicalGroupName);
            $this->indexedPoliticalGroups[$politicalGroupName] = $country;
        }

        return $this->indexedPoliticalGroups[$politicalGroupName];
    }

    private function getNationalPoliticalGroup(string $nationalPoliticalGroupName): NationalPoliticalGroup
    {
        if (count($this->indexedNationalPoliticalGroups) === 0) {
            $this->indexedNationalPoliticalGroups = $this->nationalPoliticalGroupRepository->getNationalPoliticalGroupsIndexedByName();
        }

        /* Create National Political Group if it doesn't exist */
        if (!array_key_exists($nationalPoliticalGroupName, $this->indexedNationalPoliticalGroups)) {
            $country = $this->nationalPoliticalGroupRepository->create($nationalPoliticalGroupName);
            $this->indexedNationalPoliticalGroups[$nationalPoliticalGroupName] = $country;
        }

        return $this->indexedNationalPoliticalGroups[$nationalPoliticalGroupName];
    }
}