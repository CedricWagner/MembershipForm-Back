<?php

namespace App\EventListener;

use App\Entity\Member;
use App\Service\Numerator;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Member::class)]
final class MemberCreateNumerator
{
    public function __construct(protected Numerator $numerator)
    {
    }

    public function prePersist(Member $member, PrePersistEventArgs $event): void
    {
        $member->setNum($this->numerator->getNextMemberNum());
    }
}