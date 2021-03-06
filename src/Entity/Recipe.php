<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RecipeRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=RecipeRepository::class)
 * @Vich\Uploadable
 *
 * @ApiResource(
 *     collectionOperations = {
 *          "get" = {
 *              "normalization_context" = {"groups" = {"read:recipes"}}
 *          },
 *          "post" = {
 *              "denormalization_context" = {"groups" = {"write:recipes"}}
 *          }
 *     },
 *     itemOperations = {
 *          "get" = {
 *              "normalization_context" = {"groups" = {"read:recipes", "read:recipe"}}
 *          },
 *          "put" = {
 *              "denormalization_context" = {"groups" = {"write:recipe"}}
 *          },
 *          "delete",
 *          "patch"
 *     }
 * )
 */
class Recipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"read:recipes", "write:recipes", "write:recipe", "read:orders"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"read:recipes", "write:recipes", "write:recipe", "read:order"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"read:recipes", "write:recipes", "write:recipe"})
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     *
     * @Groups({"read:recipes", "write:recipes", "write:recipe"})
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     *
     * @Groups({"read:recipes", "write:recipes", "write:recipe"})
     */
    private $ingredient;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"read:recipes", "write:recipes", "write:recipe"})
     */
    private $nbperson;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"read:recipes", "write:recipes", "write:recipe"})
     */
    private $preparationtime;

    /**
     * @ORM\Column(type="float")
     *
     * @Groups({"read:recipes", "write:recipes", "write:recipe"})
     */
    private $price;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Groups({"read:recipes", "write:recipes", "write:recipe"})
     */
    private $picture;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="recipe_images", fileNameProperty="picture")
     */
    private $imageFile;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="recipe")
     */
    private $orders;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="recipes")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"read:recipes", "write:recipes", "write:recipe"})
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="recipes")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"read:recipes", "write:recipes", "write:recipe"})
     */
    private $user;

    public function __construct()
    {
        $this->updated = new DateTime();
        $this->orders = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIngredient(): ?string
    {
        return $this->ingredient;
    }

    public function setIngredient(string $ingredient): self
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    public function getNbperson(): ?int
    {
        return $this->nbperson;
    }

    public function setNbperson(int $nbperson): self
    {
        $this->nbperson = $nbperson;

        return $this;
    }

    public function getPreparationtime(): ?string
    {
        return $this->preparationtime;
    }

    public function setPreparationtime(string $preparationtime): self
    {
        $this->preparationtime = $preparationtime;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture = null): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     */
    public function setImageFile(?File $imageFile = null)
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updated = new Datetime('now');
        }
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdated(): ?DateTimeInterface
    {
        return $this->updated;
    }

    /**
     * @param DateTimeInterface|null $updated
     * @return $this
     */
    public function setUpdated(?DateTimeInterface $updated): self
    {
        $this->updated = $updated;
        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setRecipe($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getRecipe() === $this) {
                $order->setRecipe(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

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

    public function __toString(): ?string
    {
        return $this->getName();
    }
}
