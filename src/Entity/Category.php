<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getRecette'])]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Recipe::class, mappedBy: 'category')]
    private Collection $recettes;

    public function __construct()
    {
        $this->recettes = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName();
    }

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

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecettes(): Collection
    {
        return $this->recettes;
    }

    public function addRecette(Recipe $recette): self
    {
        if (!$this->recettes->contains($recette)) {
            $this->recettes->add($recette);
            $recette->addCategory($this);
        }

        return $this;
    }

    public function removeRecette(Recipe $recette): self
    {
        if ($this->recettes->removeElement($recette)) {
            $recette->removeCategory($this);
        }

        return $this;
    }

}
