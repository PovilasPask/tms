<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TournamentRepository")
 */
class Tournament
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     * @Assert\Length(
     *      max = 1000,
     *      maxMessage = "Turnyro aprašymą turi sudaryti ne daugiau, nei {{ limit }} simbolių"
     * )
     */
    private $tournamentDesc;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $rounds;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $playoffsPlaces;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $roundsPerPair;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $groupCount;

    /**
     * @ORM\Column(type="smallint")
     */
    private $playersOnField;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     * @Assert\Length(
     *      max = 1000,
     *      maxMessage = "Turnyro taisyklių aprašymą turi sudaryti ne daugiau, nei {{ limit }} simbolių"
     * )
     */
    private $rules;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Turnyro prizų aprašymą turi sudaryti ne daugiau, nei {{ limit }} simboliai"
     * )
     */
    private $prizes;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Lokaciją turi sudaryti ne daugiau, nei {{ limit }} simboliai"
     * )
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Turnyro aikščių aprašymą turi sudaryti ne daugiau, nei {{ limit }} simboliai"
     * )
     */
    private $venue;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "ŽTurnyro kontaktų aprašymą turi sudaryti ne daugiau, nei {{ limit }} simboliai"
     * )
     */
    private $contacts;

    /**
     * @ORM\Column(type="string", length=9, unique=true)
     */
    private $code;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isStarted;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isEnded;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="tournaments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TournamentType", inversedBy="tournaments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Turnyro pavadinimą turi sudaryti ne daugiau, nei {{ limit }} simboliai"
     * )
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tournaments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Team", mappedBy="tournament")
     */
    private $teams;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Game", mappedBy="tournament")
     */
    private $games;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
        $this->games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTournamentDesc(): ?string
    {
        return $this->tournamentDesc;
    }

    public function setTournamentDesc(?string $tournamentDesc): self
    {
        $this->tournamentDesc = $tournamentDesc;

        return $this;
    }

    public function getRounds(): ?int
    {
        return $this->rounds;
    }

    public function setRounds(?int $rounds): self
    {
        $this->rounds = $rounds;

        return $this;
    }

    public function getPlayoffsPlaces(): ?int
    {
        return $this->playoffsPlaces;
    }

    public function setPlayoffsPlaces(?int $playoffsPlaces): self
    {
        $this->playoffsPlaces = $playoffsPlaces;

        return $this;
    }

    public function getRoundsPerPair(): ?int
    {
        return $this->roundsPerPair;
    }

    public function setRoundsPerPair(?int $roundsPerPair): self
    {
        $this->roundsPerPair = $roundsPerPair;

        return $this;
    }

    public function getGroupCount(): ?int
    {
        return $this->groupCount;
    }

    public function setGroupCount(?int $groupCount): self
    {
        $this->groupCount = $groupCount;

        return $this;
    }

    public function getPlayersOnField(): ?int
    {
        return $this->playersOnField;
    }

    public function setPlayersOnField(int $playersOnField): self
    {
        $this->playersOnField = $playersOnField;

        return $this;
    }

    public function getRules(): ?string
    {
        return $this->rules;
    }

    public function setRules(?string $rules): self
    {
        $this->rules = $rules;

        return $this;
    }

    public function getPrizes(): ?string
    {
        return $this->prizes;
    }

    public function setPrizes(?string $prizes): self
    {
        $this->prizes = $prizes;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getVenue(): ?string
    {
        if ($this->venue == null) {
            return 'Nera informacijos.';
        } else {
            return $this->venue;
        }
    }

    public function setVenue(?string $venue): self
    {
        $this->venue = $venue;

        return $this;
    }

    public function getContacts(): ?string
    {
        return $this->contacts;
    }

    public function setContacts(?string $contacts): self
    {
        $this->contacts = $contacts;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getIsStarted(): ?bool
    {
        return $this->isStarted;
    }

    public function setIsStarted(bool $isStarted): self
    {
        $this->isStarted = $isStarted;

        return $this;
    }

    public function getIsEnded(): ?bool
    {
        return $this->isEnded;
    }

    public function setIsEnded(bool $isEnded): self
    {
        $this->isEnded = $isEnded;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getType(): ?TournamentType
    {
        return $this->type;
    }

    public function setType(?TournamentType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): ?string
    {
        if ($this->name !== null) {
            return $this->name . ' (' . $this->getPlayersOnField() . 'X' . $this->getPlayersOnField() . ')';
        } else {
            return $this->name;
        }
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
            $team->setTournament($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->contains($team)) {
            $this->teams->removeElement($team);
            // set the owning side to null (unless already changed)
            if ($team->getTournament() === $this) {
                $team->setTournament(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->setTournament($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->contains($game)) {
            $this->games->removeElement($game);
            // set the owning side to null (unless already changed)
            if ($game->getTournament() === $this) {
                $game->setTournament(null);
            }
        }

        return $this;
    }
}
