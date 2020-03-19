<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 * @ApiResource
 */
class Tag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $tagName;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", inversedBy="tags")
     */
    private $tagProduct;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="tags")
     */
    private $category;

    public function __construct()
    {
        $this->tagProduct = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTagName(): ?string
    {
        return $this->tagName;
    }

    public function setTagName(string $tagName): self
    {
        $this->tagName = $tagName;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getTagProduct(): Collection
    {
        return $this->tagProduct;
    }

    public function addTagProduct(Product $tagProduct): self
    {
        if (!$this->tagProduct->contains($tagProduct)) {
            $this->tagProduct[] = $tagProduct;
        }

        return $this;
    }

    public function removeTagProduct(Product $tagProduct): self
    {
        if ($this->tagProduct->contains($tagProduct)) {
            $this->tagProduct->removeElement($tagProduct);
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
    public function __toString()
    {
        return $this->tagName;
    }
}
