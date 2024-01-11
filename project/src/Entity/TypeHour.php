<?php

namespace App\Entity;

use App\Repository\TypeHourRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeHourRepository::class)
 */
class TypeHour
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity=WorkHour::class, mappedBy="type_hour")
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

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

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
            $workHour->setTypeHour($this);
        }

        return $this;
    }

    public function removeWorkHour(WorkHour $workHour): self
    {
        if ($this->workHours->removeElement($workHour)) {
            // set the owning side to null (unless already changed)
            if ($workHour->getTypeHour() === $this) {
                $workHour->setTypeHour(null);
            }
        }

        return $this;
    }
}
