<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Article;
use App\Entity\ArticleImages;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Price", inversedBy="product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $price;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", mappedBy="tagProduct")
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="product", orphanRemoval=true)
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="product", orphanRemoval=true)
     */
    private $reviews;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="products")
     */
    private $category;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getPrice(): ?Price
    {
        return $this->price;
    }

    public function setPrice(?Price $price): self
    {
        $this->price = $price;

        return $this;
    }


    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addTagProduct($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeTagProduct($this);
        }

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setProduct($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getProduct() === $this) {
                $article->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setProduct($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getProduct() === $this) {
                $review->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->category->contains($category)) {
            $this->category->removeElement($category);
        }

        return $this;
    }

    public function getAllSizes()
    {
        foreach ($this->getArticles() as $article) {
            if ($article->getSize() !== null && (!isset($sizes) || (!in_array($article->getSize(), $sizes)))) {
                $sizes[] = $article->getSize();
            } elseif ($article->getSize() === null) {
                $sizes = null;
            }
        }
        return $sizes;
    }

    public function getAllColors()
    {
        foreach ($this->getArticles() as $article) {
            if ($article->getColor() !== null && (!isset($colors) || (!in_array($article->getColor(), $colors)))) {
                $colors[] = $article->getColor();
            } elseif ($article->getColor() === null) {
                $colors = null;
            }
        }
        return $colors;
    }

    public function getAllDevices()
    {
        foreach ($this->getArticles() as $article) {
            if ($article->getDevice() !== null && (!isset($devices) || (!in_array($article->getDevice(), $devices)))) {
                $devices[] = $article->getDevice();
            } elseif ($article->getDevice() === null) {
                $devices = null;
            }
        }
        return $devices;
    }
    public function __toString()
    {
        return $this->title;
        return $this->price;
    }
}
