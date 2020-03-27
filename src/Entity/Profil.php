<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfilRepository")
 */
class Profil
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $age;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="profils")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Wall", mappedBy="profilComment")
     */
    private $walls;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Reputation", mappedBy="profils")
     */
    private $reputations;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->walls = new ArrayCollection();
        $this->reputations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
     * Get the value of filename
     *
     * @return  string|null
     */ 
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set the value of filename
     *
     * @param  string|null  $filename
     *
     * @return  self
     */ 
    public function setFilename($filename)
    {
        $this->filename = $filename;

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
            $wall->addProfilComment($this);
        }

        return $this;
    }

    public function removeWall(Wall $wall): self
    {
        if ($this->walls->contains($wall)) {
            $this->walls->removeElement($wall);
            $wall->removeProfilComment($this);
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
            $reputation->addProfil($this);
        }

        return $this;
    }

    public function removeReputation(Reputation $reputation): self
    {
        if ($this->reputations->contains($reputation)) {
            $this->reputations->removeElement($reputation);
            $reputation->removeProfil($this);
        }

        return $this;
    }
}
