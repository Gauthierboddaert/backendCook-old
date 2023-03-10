<?php

namespace App\Entity;

use App\Repository\FavorisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavorisRepository::class)]
class Favoris
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Recipe::class, inversedBy: 'favoris')]
    private Collection $recette;

    #[ORM\ManyToMany(targetEntity: user::class, inversedBy: 'favoris')]
    private Collection $user;

    #[ORM\Column]
    private ?bool $isFavoris = null;

    public function __construct()
    {
        $this->recette = new ArrayCollection();
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecette(): Collection
    {
        return $this->recette;
    }

    public function addRecette(Recipe $recette): self
    {
        if (!$this->recette->contains($recette)) {
            $this->recette->add($recette);
        }

        return $this;
    }

    public function removeRecette(Recipe $recette): self
    {
        $this->recette->removeElement($recette);

        return $this;
    }

    /**
     * @return Collection<int, user>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(user $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
        }

        return $this;
    }

    public function removeUser(user $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }

    public function isIsFavoris(): ?bool
    {
        return $this->isFavoris;
    }

    public function setIsFavoris(bool $isFavoris): self
    {
        $this->isFavoris = $isFavoris;

        return $this;
    }
}
