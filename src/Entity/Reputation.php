<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReputationRepository")
 */
class Reputation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $likes;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="reputations")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Profil", inversedBy="reputations")
     */
    private $profils;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Wall", inversedBy="reputations")
     */
    private $walls;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $profil_id;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->profils = new ArrayCollection();
        $this->walls = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    /**
     * @return Collection|Profil[]
     */
    public function getProfils(): Collection
    {
        return $this->profils;
    }

    public function addProfil(Profil $profil): self
    {
        if (!$this->profils->contains($profil)) {
            $this->profils[] = $profil;
        }

        return $this;
    }

    public function removeProfil(Profil $profil): self
    {
        if ($this->profils->contains($profil)) {
            $this->profils->removeElement($profil);
        }

        return $this;
    }

    /**
     * @return Collection|Wall[]
     */
    public function getWalls(): Collection
    {
        return $this->walls;
    }

    public function addWall(Wall $wall): self
    {
        if (!$this->walls->contains($wall)) {
            $this->walls[] = $wall;
        }

        return $this;
    }

    public function removeWall(Wall $wall): self
    {
        if ($this->walls->contains($wall)) {
            $this->walls->removeElement($wall);
        }

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getProfilId(): ?int
    {
        return $this->profil_id;
    }

    public function setProfilId(int $profil_id): self
    {
        $this->profil_id = $profil_id;

        return $this;
    }
}
