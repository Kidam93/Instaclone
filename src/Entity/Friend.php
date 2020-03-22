<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FriendRepository")
 */
class Friend
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
    private $userId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $friendId;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isFriend;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="friends")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getFriendId(): ?int
    {
        return $this->friendId;
    }

    public function setFriendId(?int $friendId): self
    {
        $this->friendId = $friendId;

        return $this;
    }

    public function getIsFriend(): ?bool
    {
        return $this->isFriend;
    }

    public function setIsFriend(?bool $isFriend): self
    {
        $this->isFriend = $isFriend;

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
}
