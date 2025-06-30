<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class InfomaniakNewsletterManager implements NewsletterManagerInterface {
    
    public function __construct(private HttpClientInterface $client, private LoggerInterface $logger, private string $domain)
    {
        
    }

    /**
     * @inheritDoc
     */
    public function subscribe(string $email): bool {
        $this->logger->debug('Start newsletter subscribtion');
        try {
            $response = $this->client->request(
                'POST',
                "https://api.infomaniak.com/1/newsletters/{$this->domain}/subscribers",
                [
                    'headers' =>
                    [
                        'Authorization' => 'Ul8eaIrIc6N8g0P9ivknF3XyksK6Zc9o_oISSfej-NgGaozBxrDEDUlNofy5KVQKOWoK_H1THKdoCkCJ',
                        'Accept' => 'application/json',
                    ]
                ]
            );
            var_dump($response->getInfo());
            die();
            $statusCode = $response->getStatusCode();
            
            if ($statusCode != 200) {
                throw new \Exception($response->getContent());
            }
            
            $this->logger->notice('Subscription succeed');
            return true;
        }
        catch (\Exception $e) {
            $this->logger->error($e->getMessage());

            return false;
        }
    }
    
}