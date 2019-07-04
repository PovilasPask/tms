<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Žaidėjo vardas ir pavardė turi sudaryti ne daugiau, nei {{ limit }} simbolių"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $goalCount;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\LessThanOrEqual("5000-10-10")
     */
    private $bDate;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\Range(
     *      min = 1,
     *      max = 99,
     *      minMessage = "Žaidėjo numeris turi būti {{ limit }} arba didesnis",
     *      maxMessage = "Žaidėjo numeris turi būti {{ limit }} arba mažesnis"
     * )
     */
    private $number;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team", inversedBy="players")
     * @ORM\JoinColumn(nullable=false)
     */
    private $team;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGoalCount(): ?int
    {
        return $this->goalCount;
    }

    public function setGoalCount(int $goalCount): self
    {
        $this->goalCount = $goalCount;

        return $this;
    }

    public function getBDate(): ?\DateTimeInterface
    {
        return $this->bDate;
    }

    public function setBDate(\DateTimeInterface $bDate): self
    {
        $this->bDate = $bDate;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }
}
