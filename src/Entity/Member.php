<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\QueryParameter;
use App\Filter\GlobalSearchFilter;
use App\Repository\MemberRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ApiResource(normalizationContext: ['groups' => ['member']], paginationClientEnabled:true)]
#[ApiFilter(DateFilter::class, properties: ['date'])]
#[ApiFilter(GlobalSearchFilter::class, properties: ['search'])]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy:"AUTO")]
    #[ORM\Column]
    #[Groups('member')]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups('member')]
    private ?int $num = null;

    #[ORM\Column(length: 127)]
    #[Assert\NotBlank]
    #[Groups('member')]
    private ?string $firstname = null;

    #[ORM\Column(length: 127)]
    #[Assert\NotBlank]
    #[Groups('member')]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\Email]
    #[Assert\NotBlank]
    #[Groups('member')]
    private ?string $email = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    #[Groups('member')]
    private ?string $amount = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups('member')]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne]
    #[Assert\When(
        expression: 'this.getAmount() == 0',
        constraints: [new Assert\IsNull([], 'Vous ne pouvez pas saisir un moyen de paiement sans cotisation')]
    )]
    #[Assert\When(
        expression: 'this.getAmount() > 0',
        constraints: [new Assert\NotBlank([], 'Vous devez sélectionner un moyen de paiement')]
    )]
    #[Groups('member')]
    private ?PaymentMethod $paymentMethod = null;

    #[ORM\Column]
    #[Groups('member')]
    private ?bool $willingToVolunteer = null;
    
    #[ORM\Column]
    #[Groups('member')]
    private ?bool $subscribedToNewsletter = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNum(): ?int
    {
        return $this->num;
    }

    public function setNum(int $num): static
    {
        $this->num = $num;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getPaymentMethod(): ?PaymentMethod
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?PaymentMethod $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function isWillingToVolunteer(): ?bool
    {
        return $this->willingToVolunteer;
    }

    public function setWillingToVolunteer(bool $willingToVolunteer): static
    {
        $this->willingToVolunteer = $willingToVolunteer;

        return $this;
    }

    public function isSubscribedToNewsletter(): ?bool
    {
        return $this->subscribedToNewsletter;
    }

    public function setSubscribedToNewsletter(bool $subscribedToNewsletter): static
    {
        $this->subscribedToNewsletter = $subscribedToNewsletter;

        return $this;
    }
}
