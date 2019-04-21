<?php

namespace App\Entity\Invoice;

use App\Entity\Invoice\Invoice;
use App\Entity\Product\Product;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Invoice\ItemRepository")
 */
class Item {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual(value="0", message="A quantidade deve ser igual ou superior a 0.")
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     * @Assert\GreaterThanOrEqual(value="0", message="O preço por unidade deve ser igual ou superior a 0.")
     */
    private $pricePerUnit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Invoice\Invoice", inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="A fatura é obrigatória.")
     */
    private $invoice;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product\Product", inversedBy="invoiceItems")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="O produto é opbrigatório.")
     */
    private $product;

    /**
     * @ORM\Column(type="float")
     * @Assert\GreaterThanOrEqual(value="0", message="0 refúgo não pode ser negativo.")
     */
    private $waste;


    public function getSubTotal(): float {
        return $this->getPricePerUnit() * $this->getQuantity();
    }

    public function getTotal() : float {
        return $this->getPricePerUnit() * ($this->getQuantity() -  $this->getWaste());
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getQuantity(): ?int {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPricePerUnit(): ?float {
        return $this->pricePerUnit;
    }

    public function setPricePerUnit(float $pricePerUnit): self {
        $this->pricePerUnit = $pricePerUnit;

        return $this;
    }

    public function getInvoice(): ?Invoice {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): self {
        $this->invoice = $invoice;

        return $this;
    }

    public function getProduct(): ?Product {
        return $this->product;
    }

    public function setProduct(?Product $product): self {
        $this->product = $product;

        return $this;
    }

    public function getWaste(): ?float {
        return $this->waste;
    }

    public function setWaste(float $waste): self {
        $this->waste = $waste;

        return $this;
    }
}
