<?php

namespace App\MessageHandler;

use App\Entity\Member;
use App\Importer\ImporterMembers;
use App\Message\ImportMemberDetailedInfoMessage;
use App\Repository\MemberRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class ImportMemberDetailedInfoHandler
{
    public function __construct(
        private MemberRepository $memberRepository,
        private ImporterMembers  $importerMembers,
    ) {
    }

    public function __invoke(ImportMemberDetailedInfoMessage $message): void
    {
        $member = $this->memberRepository->find($message->getMemberId());
        if ($member instanceof Member) {
            $this->importerMembers->importMemberContacts($member);
        }
    }
}