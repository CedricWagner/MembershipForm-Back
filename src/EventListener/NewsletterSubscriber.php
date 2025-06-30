<?php

namespace App\EventListener;

use App\Entity\Member;
use App\Service\InfomaniakNewsletterManager;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Member::class)]
final class NewsletterSubscriber
{
    public function __construct(protected InfomaniakNewsletterManager $newsletterManager)
    {
    }

    public function prePersist(Member $member, PrePersistEventArgs $event): void
    {
        if ($member->isSubscribedToNewsletter()) {
            $this->newsletterManager->subscribe($member->getEmail());
        }
    }
}