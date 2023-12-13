<?php

namespace App\Command;

use App\Entity\Member;
use App\Importer\ImporterMembers;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Exception;

#[AsCommand(name: 'import-member-list')]
class ImportMemberListCommand extends Command
{
    public function __construct(
        private readonly ImporterMembers $importerMembers,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $onMemberImport = function (Member $member) use ($output) {
            $output->writeln("<info>Member imported: {$member->getMepId()} {$member->getFullName()}</info>");
        };

        try {
            $this->importerMembers->importMemberList($onMemberImport);

            return Command::SUCCESS;
        } catch (Exception $e) {
            $output->writeln(sprintf("<error>%s</error>", $e->getMessage()));

            return Command::FAILURE;
        }
    }
}