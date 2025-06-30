<?php

namespace App\Service;

interface NewsletterManagerInterface {
    
    /**
     * Send data to API to subscribe a new member.
     */
    public function subscribe(string $email): bool;
}