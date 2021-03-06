<?php

namespace App\Entity\Invoice;

use App\Entity\Company\Company;
use App\Entity\Invoice\Item;
use App\Entity\Payment\Payment;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Invoice\InvoiceRepository")
 * @UniqueEntity(
 *     fields={"number", "issuer"},
 *     message="Já existe uma fatura com este número de sequência emitida por esta empresa.",
 *     errorPath="number",
 * )
 */
class Invoice {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=16)
     * @Assert\NotNull()
     * @Assert\NotBlank(message="O número de fatura é obrigatório.")
     * @Assert\Length(min="1", max="16")
     */
    private $number;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotNull(message="O campo data é obrigatório.")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company\Company", inversedBy="issuedInvoices")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotIdenticalTo(propertyPath="client", message="O cliente e o emissor não podem ser a mesma empresa.")
     */
    private $issuer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company\Company", inversedBy="receivedInvoices")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotIdenticalTo(propertyPath="issuer", message="O cliente e o emissor não podem ser a mesma empresa.")
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Invoice\Item", mappedBy="invoice", orphanRemoval=true, cascade={"persist", "remove"})
     * @ORM\OrderBy({"quantity" = "asc"})
     * @Assert\Valid()
     */
    private $items;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Payment\Payment", mappedBy="invoice", orphanRemoval=true, cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $payments;

    public function __construct() {
        $this->items = new ArrayCollection();
        $this->payments = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getNumber(): ?string {
        return $this->number;
    }

    public function setNumber(string $number): self {
        $this->number = $number;

        return $this;
    }

    public function getDate(): ?\DateTime {
        return $this->date;
    }

    public function setDate(\DateTime $date): self {
        $this->date = $date;

        return $this;
    }

    public function getIssuer(): ?Company {
        return $this->issuer;
    }

    public function setIssuer(?Company $company): self {
        $this->issuer = $company;

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection {
        return $this->items;
    }

    public function addItem(Item $item): self {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setInvoice($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getInvoice() === $this) {
                $item->setInvoice(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Payment[]
     */
    public function getPayments(): Collection {
        return $this->payments;
    }

    public function addPayment(Payment $payment): self {
        if (!$this->payments->contains($payment)) {
            $this->payments[] = $payment;
            $payment->setInvoice($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self {
        if ($this->payments->contains($payment)) {
            $this->payments->removeElement($payment);
            // set the owning side to null (unless already changed)
            if ($payment->getInvoice() === $this) {
                $payment->setInvoice(null);
            }
        }

        return $this;
    }

    public function getClient(): ?Company {
        return $this->client;
    }

    public function setClient(?Company $client): self {
        $this->client = $client;

        return $this;
    }

    public function getSubTotal() : float {
        $items = $this->getItems();
        $total = 0;
        foreach($items as $item){
            $total += $item->getSubTotal();
        }

        return $total;
    }

    public function getTotal(): float {
        $items = $this->getItems();
        $total = 0;
        foreach($items as $item){
            $total += $item->getTotal();
        }

        return $total;
    }

    public function getTotalPaid(): float {
        $total = 0;
        foreach ($this->getPayments() as $payment) {
            $total += $payment->getAmount();
        }
        return $total;
    }

    public function getUnpaidAmount(): float {
        return $this->getTotal() - $this->getTotalPaid();
    }

    public function isPaid() : bool {
        return $this->getTotalPaid() >= $this->getTotal();
    }

    public function getMissingToPay(){
        $missing = $this->getTotal()-$this->getTotalPaid();
        return max($missing, 0);
    }

    public function getTotalQuantity() : float {
        $items = $this->getItems();
        $total = 0;
        foreach($items as $item){
            $total += $item->getQuantity();
        }

        return $total;
    }

    public function getTotalWaste() : float {
        $items = $this->getItems();
        $total = 0;
        foreach($items as $item){
            $total += $item->getWaste();
        }

        return $total;
    }
}
