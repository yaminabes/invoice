<?php

namespace App\Entity;

use App\Repository\WorkHourRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WorkHourRepository::class)
 */
class WorkHour
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Developer::class, inversedBy="workHours")
     */
    private $developper;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start_time;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end_time;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $hours_worked;

    /**
     * @ORM\ManyToOne(targetEntity=TypeHour::class, inversedBy="workHours")
     */
    private $type_hour;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $unit_price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDevelopper(): ?Developer
    {
        return $this->developper;
    }

    public function setDevelopper(?Developer $developper): self
    {
        $this->developper = $developper;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->start_time;
    }

    public function setStartTime(\DateTimeInterface $start_time): self
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->end_time;
    }

    public function setEndTime(\DateTimeInterface $end_time): self
    {
        $this->end_time = $end_time;

        return $this;
    }

    public function getHoursWorked(): ?string
    {
        return $this->hours_worked;
    }

    public function setHoursWorked(string $hours_worked): self
    {
        $this->hours_worked = $hours_worked;

        return $this;
    }

    public function getTypeHour(): ?TypeHour
    {
        return $this->type_hour;
    }

    public function setTypeHour(?TypeHour $type_hour): self
    {
        $this->type_hour = $type_hour;

        return $this;
    }

    public function getUnitPrice(): ?string
    {
        return $this->unit_price;
    }

    public function setUnitPrice(string $unit_price): self
    {
        $this->unit_price = $unit_price;

        return $this;
    }
}
