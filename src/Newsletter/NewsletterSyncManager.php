<?php
namespace App\Newsletter;

use App\Entity\Member;

class NewsletterSyncManager
{
    /**
     * @param iterable<NewsletterSyncInterface> $syncServices
     */
    public function __construct(
        private string $services,
        private iterable $syncServices
    ) {}

    public function sync(Member $member): void
    {
        foreach ($this->syncServices as $syncService) {
            $allowedServices = explode(',', $this->services);
            if (!in_array($syncService->getId(), $allowedServices, true)) {
                continue;
            }
            $syncService->sync($member);
        }
    }
}
