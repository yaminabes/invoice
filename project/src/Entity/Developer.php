<?php

namespace App\Entity;

use App\Repository\DeveloperRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeveloperRepository::class)
 */
class Developer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $matricule;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $salaire;

    /**
     * @ORM\OneToMany(targetEntity=WorkHour::class, mappedBy="developper")
     */
    private $workHours;

    public function __construct()
    {
        $this->workHours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getSalaire(): ?int
    {
        return $this->salaire;
    }

    public function setSalaire(?int $salaire): self
    {
        $this->salaire = $salaire;

        return $this;
    }

    /**
     * @return Collection<int, WorkHour>
     */
    public function getWorkHours(): Collection
    {
        return $this->workHours;
    }

    public function addWorkHour(WorkHour $workHour): self
    {
        if (!$this->workHours->contains($workHour)) {
            $this->workHours[] = $workHour;
            $workHour->setDevelopper($this);
        }

        return $this;
    }

    public function removeWorkHour(WorkHour $workHour): self
    {
        if ($this->workHours->removeElement($workHour)) {
            // set the owning side to null (unless already changed)
            if ($workHour->getDevelopper() === $this) {
                $workHour->setDevelopper(null);
            }
        }

        return $this;
    }
}
