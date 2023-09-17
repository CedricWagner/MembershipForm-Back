<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\TotalPayments;
use App\Repository\MemberRepository;
use DateTime;

class TotalPaymentsStateProvider implements ProviderInterface
{
    public function __construct(private MemberRepository $memberRepository)
    {
        
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $dateStart = isset($context['filters']['dateStart']) ? new DateTime($context['filters']['dateStart']) : new DateTime();
        $dateEnd = isset($context['filters']['dateEnd']) ? new DateTime($context['filters']['dateEnd']) : new DateTime();
        
        $members = $this->memberRepository->findByDateRange($dateStart, $dateEnd);
        $totalPayments =  new TotalPayments();
        $totalPayments->setDateStart($dateStart); 
        $totalPayments->setDateEnd($dateEnd);
        $total = 0;
        foreach ($members as $member) {
            $total += $member->getAmount();
        }
        $totalPayments->setTotal($total);
        
        return $totalPayments;
    }
}
