<?php

namespace App\Service;

use App\Repository\MemberRepository;

class Numerator {

    public function __construct(
        protected int $startingNum,
        protected MemberRepository $memberRepository
        )
    {
    }

    public function getNextMemberNum(): int {
        $lastMember = $this->memberRepository->findOneBy([], [
            'num' => 'DESC'
        ]);

        if (!$lastMember) {
            return $this->startingNum;
        }

        return $lastMember->getNum() >= $this->startingNum ? $lastMember->getNum() + 1 : $this->startingNum;
    }
}