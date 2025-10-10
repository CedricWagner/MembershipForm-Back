<?php 

namespace App\Newsletter;

use App\Entity\Member;

interface NewsletterSyncInterface
{
    public function sync(Member $member): void;

    public function getId(): string;
}
