<?php

namespace App\Entity\Vehicle;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Vehicle\OperationTypeRepository")
 */
class OperationType {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=256)
     * @Assert\NotNull(message="O nome é obrigatório.")
     * @Assert\Length(max=256, message="O nome é pode ter no máximo 256 caracteres.")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vehicle\Operation", mappedBy="operationType", orphanRemoval=true)
     */
    private $operations;

    public function __construct() {
        $this->operations = new ArrayCollection();
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

    /**
     * @return Collection|Operation[]
     */
    public function getOperations(): Collection {
        return $this->operations;
    }

    public function addOperation(Operation $operation): self {
        if (!$this->operations->contains($operation)) {
            $this->operations[] = $operation;
            $operation->setOperationType($this);
        }

        return $this;
    }

    public function removeOperation(Operation $operation): self {
        if ($this->operations->contains($operation)) {
            $this->operations->removeElement($operation);
            // set the owning side to null (unless already changed)
            if ($operation->getOperationType() === $this) {
                $operation->setOperationType(null);
            }
        }

        return $this;
    }
}
