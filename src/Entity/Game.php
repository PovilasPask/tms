<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 */
class Game
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $round;

    /**
     * @ORM\Column(type="smallint")
     */
    private $gameNr;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPlayoffsGame;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $playoffsRound;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $groupNr;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $homeScore;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $awayScore;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="games")
     */
    private $referee;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tournament", inversedBy="games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournament;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team")
     * @ORM\JoinColumn(nullable=false)
     */
    private $homeTeam;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team")
     * @ORM\JoinColumn(nullable=false)
     */
    private $awayTeam;

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

    public function getRound(): ?int
    {
        return $this->round;
    }

    public function setRound(?int $round): self
    {
        $this->round = $round;

        return $this;
    }

    public function getGameNr(): ?int
    {
        return $this->gameNr;
    }

    public function setGameNr(int $gameNr): self
    {
        $this->gameNr = $gameNr;

        return $this;
    }

    public function getIsPlayoffsGame(): ?bool
    {
        return $this->isPlayoffsGame;
    }

    public function setIsPlayoffsGame(bool $isPlayoffsGame): self
    {
        $this->isPlayoffsGame = $isPlayoffsGame;

        return $this;
    }

    public function getPlayoffsRound(): ?int
    {
        return $this->playoffsRound;
    }

    public function setPlayoffsRound(?int $playoffsRound): self
    {
        $this->playoffsRound = $playoffsRound;

        return $this;
    }

    public function getGroupNr(): ?int
    {
        return $this->groupNr;
    }

    public function setGroupNr(?int $groupNr): self
    {
        $this->groupNr = $groupNr;

        return $this;
    }

    public function getHomeScore(): ?int
    {
        return $this->homeScore;
    }

    public function setHomeScore(?int $homeScore): self
    {
        $this->homeScore = $homeScore;

        return $this;
    }

    public function getAwayScore(): ?int
    {
        return $this->awayScore;
    }

    public function setAwayScore(?int $awayScore): self
    {
        $this->awayScore = $awayScore;

        return $this;
    }

    public function getReferee(): ?User
    {
        return $this->referee;
    }

    public function setReferee(?User $referee): self
    {
        $this->referee = $referee;

        return $this;
    }

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): self
    {
        $this->tournament = $tournament;

        return $this;
    }

    public function getHomeTeam(): ?Team
    {
        return $this->homeTeam;
    }

    public function setHomeTeam(?Team $homeTeam): self
    {
        $this->homeTeam = $homeTeam;

        return $this;
    }

    public function getAwayTeam(): ?Team
    {
        return $this->awayTeam;
    }

    public function setAwayTeam(?Team $awayTeam): self
    {
        $this->awayTeam = $awayTeam;

        return $this;
    }
}
