<?php

namespace App\Newsletter;

use App\Entity\Member;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use MailchimpMarketing\ApiClient;
use MailchimpMarketing\ApiException;

class MailchimpNewsletterSync implements NewsletterSyncInterface
{
    
    private string $dc; // Data center (us6, us21, etc.)

    public function __construct(
        private string $mailchimpApiKey,
        private string $mailchimpBalise,
        private string $mailchimpListId,
        private LoggerInterface $logger,
    ) {
        // Extract datacenter from key.
        $this->dc = substr($this->mailchimpApiKey, strpos($this->mailchimpApiKey, '-') + 1);
    }

    public function sync(Member $member): void
    {
        $mailchimp = new ApiClient();
        $mailchimp->setConfig([
            'apiKey' => $this->mailchimpApiKey,
            'server' => $this->dc,
        ]);

        try {
            $mailchimp->lists->setListMember($this->mailchimpListId, $member->getEmail(), [
                "email_address" => $member->getEmail(),
                "status_if_new" => "subscribed",
                "merge_fields" => [
                    'FNAME' => $member->getFirstName() ?? '',
                    'LNAME' => $member->getLastName() ?? '',
                    'MMERGE3' => $member->getDate()?->format('Y-m-d') ?? '',
                    'MMERGE4' => "Oui", // Field "Inscrit à la newsletter ?"
                ],
                "tags" => [$this->mailchimpBalise],
            ]);
            $this->logger->notice('Sync succeed: ' . $member->getEmail());
        } catch (ApiException|RequestException $e) {
            $this->logger->error('Sync failed: ' . $e->getMessage());
        }
    }

    public function getId(): string
    {
        return 'mailchimp';
    }
}
