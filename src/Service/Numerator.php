<?php

namespace App\Service;

use App\Repository\MemberRepository;

class Numerator {

    public function __construct(protected MemberRepository $memberRepository)
    {
    }

    public function getNextMemberNum(): int {
        $lastMember = $this->memberRepository->findOneBy([], [
            'num' => 'DESC'
        ]);

        return $lastMember ? ($lastMember->getNum() + 1) : 1;
    }
}