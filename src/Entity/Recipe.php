<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\User;
use App\Entity\Category;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Image;
use Gedmo\Timestampable\Traits\TimestampableEntity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getRecette'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getRecette'])]
    private ?string $descriptions = null;

    #[ORM\ManyToOne(inversedBy: 'recettes')]
    #[Groups(['getRecette'])]
    private ?user $users = null;

    #[ORM\ManyToMany(targetEntity: category::class, inversedBy: 'recettes')]
    #[Groups(['getRecette'])]
    private Collection $category;

    #[ORM\ManyToMany(targetEntity: Favoris::class, mappedBy: 'recette')]
    private Collection $favoris;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?image $image = null;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: Like::class)]
    private Collection $likes;

    #[ORM\ManyToMany(targetEntity: Ingredient::class, mappedBy: 'recette')]
    private Collection $ingredients;

    #[ORM\Column]
    private ?int $creationTime = null;

    #[ORM\OneToMany(mappedBy: 'recipe', targetEntity: RecipeStep::class,cascade: ['persist', 'remove'])]
    private Collection $recipeStep;

    #[ORM\Column]
    private ?int $numberOfPersons = null;

    #[ORM\Column(nullable: true)]
    private ?float $Lipide = null;

    #[ORM\Column(nullable: true)]
    private ?float $glucide = null;

    #[ORM\Column(nullable: true)]
    private ?float $proteine = null;


    public function __toString(): string
    {
        return ($this->getName() !== null) ? $this->getName() : '';
    }

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->category = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->favoris = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->ingredients = new ArrayCollection();
        $this->recipeStep = new ArrayCollection();
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

    public function getDescriptions(): ?string
    {
        return $this->descriptions;
    }

    public function setDescriptions(string $descriptions): self
    {
        $this->descriptions = $descriptions;

        return $this;
    }

    public function getUsers(): ?user
    {
        return $this->users;
    }

    public function setUsers(?user $users): self
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return Collection<int, category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(category $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, Favoris>
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavoris(Favoris $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris->add($favori);
            $favori->addRecette($this);
        }

        return $this;
    }

    public function removeFavori(Favoris $favori): self
    {
        if ($this->favoris->removeElement($favori)) {
            $favori->removeRecette($this);
        }

        return $this;
    }

    public function getImage(): ?image
    {
        return $this->image;
    }

    public function setImage(?image $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setRecipe($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getRecipe() === $this) {
                $like->setRecipe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
            $ingredient->addRecette($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->ingredients->removeElement($ingredient)) {
            $ingredient->removeRecette($this);
        }

        return $this;
    }

    public function getCreationTime(): ?int
    {
        return $this->creationTime;
    }

    public function setCreationTime(int $creationTime): self
    {
        $this->creationTime = $creationTime;

        return $this;
    }

    /**
     * @return Collection<int, RecipeStep>
     */
    public function getRecipeStep(): Collection
    {
        return $this->recipeStep;
    }

    public function addRecipeStep(RecipeStep $recipeStep): self
    {
        if (!$this->recipeStep->contains($recipeStep)) {
            $this->recipeStep->add($recipeStep);
            $recipeStep->setRecipe($this);
        }

        return $this;
    }

    public function removeRecipeStep(RecipeStep $recipeStep): self
    {
        if ($this->recipeStep->removeElement($recipeStep)) {
            // set the owning side to null (unless already changed)
            if ($recipeStep->getRecipe() === $this) {
                $recipeStep->setRecipe(null);
            }
        }

        return $this;
    }

    public function getNumberOfPersons(): ?int
    {
        return $this->numberOfPersons;
    }

    public function setNumberOfPersons(int $numberOfPersons): self
    {
        $this->numberOfPersons = $numberOfPersons;

        return $this;
    }

    public function getLipide(): ?float
    {
        return $this->Lipide;
    }

    public function setLipide(?float $Lipide): self
    {
        $this->Lipide = $Lipide;

        return $this;
    }

    public function getGlucide(): ?float
    {
        return $this->glucide;
    }

    public function setGlucide(?float $glucide): self
    {
        $this->glucide = $glucide;

        return $this;
    }

    public function getProteine(): ?float
    {
        return $this->proteine;
    }

    public function setProteine(?float $proteine): self
    {
        $this->proteine = $proteine;

        return $this;
    }
}
