<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActivityRepository::class)
 */
class Activity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="activities")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Supplement::class, inversedBy="activities")
     */
    private $supplements;

    public function __construct()
    {
        $this->supplements = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
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

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Supplement>
     */
    public function getSupplements(): Collection
    {
        return $this->supplements;
    }

    public function addSupplement(Supplement $supplement): self
    {
        if (!$this->supplements->contains($supplement)) {
            $this->supplements[] = $supplement;
        }

        return $this;
    }

    public function removeSupplement(Supplement $supplement): self
    {
        $this->supplements->removeElement($supplement);

        return $this;
    }

    /**
     * Calculate the total cost for the activity, considering the user's basic cost and supplement percentage.
     *
     * @return float
     */
    public function calculateTotalCost(): float
    {
        $user = $this->getUser();
        $basicCost = $user ? $user->getBasicCost() : 0;

        $totalCost = $basicCost;

        foreach ($this->getSupplements() as $supplement) {
            if ($this->isStatus()) {
                $totalCost += ($basicCost * ($supplement->getPercentage() / 100));
            }
        }

        return $totalCost;
    }
    public function __toString(): string
    {
        return $this->user ;
    }
}