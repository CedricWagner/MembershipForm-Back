<?php

namespace App\EventListener;

use App\Entity\Member;
use App\Newsletter\NewsletterSyncManager;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Member::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Member::class)]
final class NewsletterRegistrationListener
{
    public function __construct(
        private readonly NewsletterSyncManager $syncManager
    )
    {
    }

    public function postPersist(Member $member, PostPersistEventArgs $event): void
    {
        $this->syncIfSubscribed($member);
    }

    public function postUpdate(Member $member, PostUpdateEventArgs $event): void
    {
        $this->syncIfSubscribed($member);
    }

    private function syncIfSubscribed(Member $member): void
    {
        if (!$member->isSubscribedToNewsletter()) {
            return;
        }

        $this->syncManager->sync($member);
    }
}