<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Recipe::class, inversedBy: 'ingredients')]
    private Collection $recette;

    #[ORM\Column(nullable: true)]
    private ?float $proteines = null;

    #[ORM\Column(nullable: true)]
    private ?float $lipides = null;

    #[ORM\Column(nullable: true)]
    private ?float $glucides = null;

    public function __construct()
    {
        $this->recette = new ArrayCollection();
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

    public function getProteines(): ?float
    {
        return $this->proteines;
    }

    public function setProteines(?float $proteines): self
    {
        $this->proteines = $proteines;

        return $this;
    }

    public function getLipides(): ?float
    {
        return $this->lipides;
    }

    public function setLipides(?float $lipides): self
    {
        $this->lipides = $lipides;

        return $this;
    }

    public function getGlucides(): ?float
    {
        return $this->glucides;
    }

    public function setGlucides(?float $glucides): self
    {
        $this->glucides = $glucides;

        return $this;
    }

}
