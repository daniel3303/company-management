<?php

namespace App\Entity\Company;

use App\Entity\Invoice\Invoice;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Company\CompanyRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorMap({"managedCompany" = "ManagedCompany", "company" = "Company"})
 * @Serializer\AccessType("public_method")
 * @Serializer\ReadOnly()
 */
class Company {
    public const COMPANY = "COMPANY";
    public const MANAGED_COMPANY = "MANAGED_COMPANY";

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     * @Assert\NotBlank(message="O nome da empresa é obrigatório.")
     * @Assert\NotNull()
     * @Assert\Length(min=2, max=64)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=16, unique=true)
     * @Assert\NotBlank(message="O NIF é obrigatório.")
     * @Assert\NotNull()
     * @Assert\Length(min=9, max=9)
     */
    private $nif;

    /**
     * @ORM\Column(type="string", length=256)
     * @Assert\NotBlank(message="A morada é obrigatória.")
     * @Assert\NotNull()
     * @Assert\Length(min=1, max=256)
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Invoice\Invoice", mappedBy="issuer", orphanRemoval=true)
     * @Serializer\MaxDepth(1)
     */
    private $issuedInvoices;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Invoice\Invoice", mappedBy="client", orphanRemoval=true)
     * @Serializer\MaxDepth(1)
     */
    private $receivedInvoices;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="companies")
     * @Serializer\Exclude()
     */
    private $user;

    public function __construct() {
        $this->issuedInvoices = new ArrayCollection();
        $this->receivedInvoices = new ArrayCollection();
    }

    public function getType(): string {
        return self::COMPANY;
    }

    /**
     * @return int|null
     */
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

    public function getNif(): ?string {
        return $this->nif;
    }

    public function setNif(string $nif): self {
        $this->nif = $nif;

        return $this;
    }

    public function getAddress(): ?string {
        return $this->address;
    }

    public function setAddress(string $address): self {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection|Invoice[]
     */
    public function getIssuedInvoices(): Collection {
        return $this->issuedInvoices;
    }

    public function addIssuedInvoice(Invoice $invoice): self {
        if (!$this->issuedInvoices->contains($invoice)) {
            $this->issuedInvoices[] = $invoice;
            $invoice->setIssuer($this);
        }

        return $this;
    }

    public function removeIssuedInvoice(Invoice $invoice): self {
        if ($this->issuedInvoices->contains($invoice)) {
            $this->issuedInvoices->removeElement($invoice);
            // set the owning side to null (unless already changed)
            if ($invoice->getIssuer() === $this) {
                $invoice->setIssuer(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User {
        return $this->user;
    }

    public function setUser(?User $user): self {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Invoice[]
     */
    public function getReceivedInvoices(): Collection {
        return $this->receivedInvoices;
    }

    public function addReceivedInvoice(Invoice $receivedInvoice): self {
        if (!$this->receivedInvoices->contains($receivedInvoice)) {
            $this->receivedInvoices[] = $receivedInvoice;
            $receivedInvoice->setClient($this);
        }

        return $this;
    }

    public function removeReceivedInvoice(Invoice $receivedInvoice): self {
        if ($this->receivedInvoices->contains($receivedInvoice)) {
            $this->receivedInvoices->removeElement($receivedInvoice);
            // set the owning side to null (unless already changed)
            if ($receivedInvoice->getClient() === $this) {
                $receivedInvoice->setClient(null);
            }
        }

        return $this;
    }

    public function getTotalInvoiced(): float {
        $invoices = $this->getIssuedInvoices();
        $total = 0;
        foreach($invoices as $invoice){
            $total += $invoice->getTotal();
        }

        return $total;
    }
}
