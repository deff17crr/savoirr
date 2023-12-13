<?php

namespace App\Importer;

use App\Manager\MemberManager;

readonly class ImporterMembers
{
    public function __construct(
        private DataFetcher                 $dataFetcher,
        private MemberManager $MemberManager,
    ) {
    }

    public function importMemberList(): void
    {
        $iterator = $this->dataFetcher->getMemberListIterator();
        foreach ($iterator as $memberData) {
            $this->MemberManager->createMember($memberData);
        }
    }
}