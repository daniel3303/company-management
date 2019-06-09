<?php

namespace App\Entity\Vehicle;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Vehicle\OperationRepository")
 */
class Operation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vehicle\Vehicle", inversedBy="operations")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="O veículo é obrigatório.")
     */
    private $vehicle;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vehicle\OperationType", inversedBy="operations")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="O tipo de operação é obrigatório.")
     */
    private $operationType;

    /**
     * @ORM\Column(type="string", length=2096, nullable=true)
     * @Assert\Length(max=2096, maxMessage="As notas podem no máximo ter 2096 caracteres.")
     */
    private $notes;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(message="O preço é obrigatório.")
     * @Assert\GreaterThanOrEqual(0, message="O preço deve ser igual ou superior a 0.")
     *
     */
    private $price;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): self
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    public function getOperationType(): ?OperationType
    {
        return $this->operationType;
    }

    public function setOperationType(?OperationType $operationType): self
    {
        $this->operationType = $operationType;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
