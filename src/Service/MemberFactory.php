<?php

namespace App\Service;

use App\Entity\Member;
use App\Entity\PaymentMethod;
use App\Entity\User;
use App\Repository\MemberRepository;
use App\Repository\PaymentMethodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MemberFactory
{
    public function __construct(private EntityManagerInterface $entityManager, private PaymentMethodRepository $paymentMethodRepository, private MemberRepository $memberRepository)
    {
    }

    /**
     * Create member entity.
     */
    public function createMember(
        string $firstname,
        string $lastname,
        string $email,
        float $amount,
        \DateTime $date,
        ?PaymentMethod $paymentMethod,
        bool $willingToVolunteer,
        bool $subscribedToNewsletter
    ): Member {
        $member = new Member();
        $member->setFirstname($firstname);
        $member->setLastname($lastname);
        $member->setEmail($email);
        $member->setAmount($amount);
        $member->setDate($date);
        if ($paymentMethod) {
            $member->setPaymentMethod($paymentMethod);
        }
        $member->setWillingToVolunteer($willingToVolunteer);
        $member->setSubscribedToNewsletter($subscribedToNewsletter);

        $this->entityManager->persist($member);
        $this->entityManager->flush();

        return $member;
    }

    /**
     * Create member entity from CSV values.
     * 
     * @throws \Exception
     */
    public function createOrUpdateMemberFromCSVValues(
        string $num,
        string $firstname,
        string $lastname,
        string $email,
        string $amount,
        string $date,
        string $paymentMethod,
        string $willingToVolunteer,
        string $subscribedToNewsletter
        ): Member
    {

        $parsedNum = $this->getNumFromCSVValue($num);
        if (!$parsedNum) {
            throw new \Exception(sprintf('Incorrect "num" field from CSV: %s', $num));
        }
        
        $member = $this->memberRepository->findOneBy(['num' => $parsedNum]);

        if (!$member) {
            $member = new Member();
        }

        if ($paymentMethod == '') {
            $paymentMethodObj = NULL;
        } else {
            $paymentMethodObj = $this->paymentMethodRepository->findOneBy(['name' => $paymentMethod]);
            if (!$paymentMethodObj) {
                throw new \Exception(sprintf('Incorrect "paymentMethod" field from CSV: %s', $paymentMethod));
            }
        }

        $member->setNum($parsedNum);
        $member->setFirstname($firstname);
        $member->setLastname($lastname);
        $member->setEmail($email);
        $member->setAmount($amount);
        $member->setDate(\DateTime::createFromFormat('d/m/Y', $date));
        $member->setPaymentMethod($paymentMethodObj);
        $member->setWillingToVolunteer($this->getBooleanFromCSVValue($willingToVolunteer));
        $member->setSubscribedToNewsletter($this->getBooleanFromCSVValue($subscribedToNewsletter));

        $this->entityManager->persist($member);
        $this->entityManager->flush();

        return $member;
    }

    /**
     * Return boolean of string value from CSV.
     */
    protected function getBooleanFromCSVValue(string $value) {
        return $value == 'Oui' ? TRUE : FALSE;
    }

    /**
     * Return num from CSV num.
     * 
     * The value retrieved is in format "00W-0000".
     */
    protected function getNumFromCSVValue(string $value) {
        
        if (preg_match('/\d+$/', $value, $matches)) {
            $result = $matches[0];
            return $result;
        } else {
            return FALSE;
        }
    }
}
