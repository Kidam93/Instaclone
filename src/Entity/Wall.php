<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WallRepository")
 */
class Wall
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="walls")
     */
    private $userComment;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Profil", inversedBy="walls")
     */
    private $profilComment;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Reputation", mappedBy="walls")
     */
    private $reputations;

    public function __construct()
    {
        $this->date = new DateTime();
        $this->userComment = new ArrayCollection();
        $this->profilComment = new ArrayCollection();
        $this->reputations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

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

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserComment(): Collection
    {
        return $this->userComment;
    }

    public function addUserComment(User $userComment): self
    {
        if (!$this->userComment->contains($userComment)) {
            $this->userComment[] = $userComment;
        }

        return $this;
    }

    public function removeUserComment(User $userComment): self
    {
        if ($this->userComment->contains($userComment)) {
            $this->userComment->removeElement($userComment);
        }

        return $this;
    }

    /**
     * @return Collection|Profil[]
     */
    public function getProfilComment(): Collection
    {
        return $this->profilComment;
    }

    public function addProfilComment(Profil $profilComment): self
    {
        if (!$this->profilComment->contains($profilComment)) {
            $this->profilComment[] = $profilComment;
        }

        return $this;
    }

    public function removeProfilComment(Profil $profilComment): self
    {
        if ($this->profilComment->contains($profilComment)) {
            $this->profilComment->removeElement($profilComment);
        }

        return $this;
    }

    /**
     * @return Collection|Reputation[]
     */
    public function getReputations(): Collection
    {
        return $this->reputations;
    }

    public function addReputation(Reputation $reputation): self
    {
        if (!$this->reputations->contains($reputation)) {
            $this->reputations[] = $reputation;
            $reputation->addWall($this);
        }

        return $this;
    }

    public function removeReputation(Reputation $reputation): self
    {
        if ($this->reputations->contains($reputation)) {
            $this->reputations->removeElement($reputation);
            $reputation->removeWall($this);
        }

        return $this;
    }
}
