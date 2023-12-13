<?php

namespace App\Importer;

use App\Entity\Member;
use App\Manager\MemberManager;
use App\Message\ImportMemberDetailedInfoMessage;
use App\Repository\MemberContactRepository;
use Symfony\Component\Messenger\MessageBusInterface;
use DateTime;

readonly class ImporterMembers
{
    public function __construct(
        private DataFetcher   $dataFetcher,
        private MemberManager $memberManager,
        private MessageBusInterface $messageBus,
        private MemberContactRepository $memberContactRepository,
    ) {
    }

    public function importMemberList($onMemberImport = null): void
    {
        $iterator = $this->dataFetcher->getMemberListIterator();
        foreach ($iterator as $memberData) {
            $existedMember = $this->memberManager->findByMepId($memberData->id);
            if ($existedMember instanceof Member) {
                continue;
            }

            $member = $this->memberManager->createMember($memberData);

            $this->messageBus->dispatch(new ImportMemberDetailedInfoMessage($member->getId()));

            if (is_callable($onMemberImport)) {
                $onMemberImport($member);
            }
        }
    }

    public function importMemberContacts(Member $member): void
    {
        if ($member->getContactsSavedAt() !== null) {
            return;
        }

        $iterator = $this->dataFetcher->getMemberContactsIterator($member);
        foreach ($iterator as $memberContactData) {
            $this->memberContactRepository->createContact($member, $memberContactData);
        }

        $member->setContactsSavedAt(new DateTime('now'));
        $this->memberManager->save($member);
    }
}