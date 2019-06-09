<?php

namespace App\Entity\Vehicle;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\Vehicle\VehicleRepository")
 * @UniqueEntity(fields={"plate"}, errorPath="plate", message="Já existe um veículo registado com a matrícula {{ value }}.")
 */
class Vehicle {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=256)
     * @Assert\NotNull(message="O modelo é obrigatório.")
     * @Assert\Length(max=256, maxMessage="O modelo pode ter 256 caracteres no máximo.")
     */
    private $model;

    /**
     * @ORM\Column(type="string", length=32, unique=true)
     * @Assert\NotNull(message="A matrícula é obrigatória.")
     * @Assert\Length(max=32, maxMessage="A matrícula pode no máximo ter 32 caracteres.")
     */
    private $plate;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(message="O ano é obrigatório.")
     * @Assert\GreaterThan(0, message="O ano deve ser maior que 0.")
     * @Assert\DivisibleBy(1, message="O ano não pode ser décimal.")
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=4096, nullable=true)
     * @Assert\Length(max="4096", maxMessage="As notas podem ter no máximo 4096 caracteres.")
     */
    private $notes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vehicle\Operation", mappedBy="vehicle", orphanRemoval=true)
     */
    private $operations;


    public function __construct() {
        $this->operations = new ArrayCollection();
        $this->setNotes("");
    }


    public function getId(): ?int {
        return $this->id;
    }

    public function getModel(): ?string {
        return $this->model;
    }

    public function setModel(string $model): self {
        $this->model = $model;

        return $this;
    }

    public function getPlate(): ?string {
        return $this->plate;
    }

    public function setPlate(string $plate): self {
        $this->plate = $plate;

        return $this;
    }

    public function getYear(): ?int {
        return $this->year;
    }

    public function setYear(int $year): self {
        $this->year = $year;

        return $this;
    }

    public function getNotes(): ?string {
        return $this->notes;
    }

    public function setNotes(?string $notes): self {
        $this->notes = $notes;

        return $this;
    }

    /**
     * @return Collection|Operation[]
     */
    public function getOperations(): Collection {
        return $this->operations;
    }

    public function addOperation(Operation $operation): self {
        if (!$this->operations->contains($operation)) {
            $this->operations[] = $operation;
            $operation->setVehicle($this);
        }

        return $this;
    }

    public function removeOperation(Operation $operation): self {
        if ($this->operations->contains($operation)) {
            $this->operations->removeElement($operation);
            // set the owning side to null (unless already changed)
            if ($operation->getVehicle() === $this) {
                $operation->setVehicle(null);
            }
        }

        return $this;
    }


}
