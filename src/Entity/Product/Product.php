<?php

namespace App\Entity\Product;

use App\Entity\Company\Company;
use App\Entity\Invoice\Item;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;


/**
 * @ORM\Entity(repositoryClass="App\Repository\Product\ProductRepository")
 * @Serializer\AccessType("public_method")
 * @Serializer\ReadOnly()
 */
class Product {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     * @Assert\NotBlank(message="O nome do produto é obrigatório.")
     * @Assert\NotNull()
     * @Assert\Length(min=2, max=128)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=1024)
     * @Assert\NotNull()
     * @Assert\Length(max=1024)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Invoice\Item", mappedBy="product", orphanRemoval=true)
     * @Serializer\Exclude()
     */
    private $invoiceItems;

    /**
     * @ORM\Column(type="string", length=256, nullable=true)
     */
    private $unit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product\Category", inversedBy="products")
     * @Assert\NotNull(message="Selecione uma categoria.")
     */
    private $category;

    public function __construct()
    {
        $this->invoiceItems = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(string $description): self {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getInvoiceItems(): Collection
    {
        return $this->invoiceItems;
    }

    public function addInvoiceItem(Item $invoiceItem): self
    {
        if (!$this->invoiceItems->contains($invoiceItem)) {
            $this->invoiceItems[] = $invoiceItem;
            $invoiceItem->setProduct($this);
        }

        return $this;
    }

    public function removeInvoiceItem(Item $invoiceItem): self
    {
        if ($this->invoiceItems->contains($invoiceItem)) {
            $this->invoiceItems->removeElement($invoiceItem);
            // set the owning side to null (unless already changed)
            if ($invoiceItem->getProduct() === $this) {
                $invoiceItem->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * Returns a list of companies who bought this product before.
     * @return Company[]|Collection
     */
    public function getBuyingCompanies() : Collection{
        $companies = new ArrayCollection();
        foreach ($this->getInvoiceItems() as $invoiceItem){
            $company = $invoiceItem->getInvoice()->getClient();
            if(!$companies->contains($company)){
                $companies->add($company);
            }
        }

        return $companies;
    }

    /**
     * Returns a list of the companies who sell this product before
     * @return Company[]|Collection
     */
    public function getSellingCompanies() : Collection{
        $companies = new ArrayCollection();
        foreach ($this->getInvoiceItems() as $invoiceItem){
            $company = $invoiceItem->getInvoice()->getIssuer();
            if(!$companies->contains($company)){
                $companies->add($company);
            }
        }

        return $companies;
    }

    public function getLowestPriceItem() : ?Item{
        /** @var $best Item|null */
        $best = null;
        foreach ($this->getInvoiceItems() as $item){
            if($best === null || $best->getPricePerUnit() > $item->getPricePerUnit()){
                $best = $item;
            }
        }

        return $best;
    }

    public function getHighestPriceItem() : ?Item{
        /** @var $best Item|null */
        $best = null;
        foreach ($this->getInvoiceItems() as $item){
            if($best === null || $best->getPricePerUnit() < $item->getPricePerUnit()){
                $best = $item;
            }
        }

        return $best;
    }

    public function getTotalQuantitySold() : float{
        $total = 0.0;
        foreach ($this->getInvoiceItems() as $item){
            $total += $item->getQuantity();
        }
        return $total;
    }

    public function getTotalPrice() : float{
        $total = 0.0;
        foreach ($this->getInvoiceItems() as $item){
            $total += $item->getTotal();
        }
        return $total;
    }

    public function getAveragePrice() : float {
        if($this->getTotalQuantitySold() == 0){
            return 0;
        }
        return $this->getTotalPrice() / $this->getTotalQuantitySold();
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

}
