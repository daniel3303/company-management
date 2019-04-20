<?php

namespace App\Entity\Payment;

use App\Entity\Invoice\Invoice;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Payment\PaymentRepository")
 */
class Payment {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank(message="O método de pagamento é obrigatório.")
     * @Assert\NotNull()
     * @Assert\Length(min=1, max=32)
     */
    private $method;

    /**
     * @ORM\Column(type="float")
     * @Assert\GreaterThanOrEqual(value="0")
     */
    private $amount;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotNull(message="O campo data é obrigatório.")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Invoice\Invoice", inversedBy="payments")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull()
     */
    private $invoice;

    /**
     * @ORM\Column(type="string", length=2048)
     * @Assert\NotNull(message="O campo descrição é obrigatório.")
     */
    private $description;

    public function getId(): ?int {
        return $this->id;
    }

    public function getMethod(): ?string {
        return $this->method;
    }

    public function setMethod(string $method): self {
        $this->method = $method;

        return $this;
    }

    public function getAmount(): ?float {
        return $this->amount;
    }

    public function setAmount(float $amount): self {
        $this->amount = $amount;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self {
        $this->date = $date;

        return $this;
    }

    public function getInvoice(): ?Invoice {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): self {
        $this->invoice = $invoice;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
