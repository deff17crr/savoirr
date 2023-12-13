<?php

namespace App\Message;

readonly class ImportMemberDetailedInfoMessage
{
    public function __construct(
        private int $memberId
    ) {
    }

    public function getMemberId(): int
    {
        return $this->memberId;
    }
}