<?php

namespace App\Controller;

use App\Importer\ImporterMembers;
use App\Repository\MemberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;


#[AsController]
class LuckyController extends AbstractController
{
    public function __construct(
        private readonly ImporterMembers $importerMembers,
        private readonly MemberRepository $memberRepository,
    )
    {

    }

    #[Route("/lucky")]
    public function lucky()
    {
        $member = $this->memberRepository->find(1);

        $this->importerMembers->importMemberContacts($member);
    }
}