<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $size;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $device;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArticleImages", mappedBy="article", orphanRemoval=true)
     */
    private $articleImages;

    public function __construct()
    {
        $this->articleImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getDevice(): ?string
    {
        return $this->device;
    }

    public function setDevice(?string $device): self
    {
        $this->device = $device;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return Collection|ArticleImages[]
     */
    public function getArticleImages(): Collection
    {
        return $this->articleImages;
    }

    public function addArticleImage(ArticleImages $articleImage): self
    {
        if (!$this->articleImages->contains($articleImage)) {
            $this->articleImages[] = $articleImage;
            $articleImage->setImage($this);
        }

        return $this;
    }

    public function removeArticleImage(ArticleImages $articleImage): self
    {
        if ($this->articleImages->contains($articleImage)) {
            $this->articleImages->removeElement($articleImage);
            // set the owning side to null (unless already changed)
            if ($articleImage->getImage() === $this) {
                $articleImage->setImage(null);
            }
        }

        return $this;
    }

    public function getStockMessage() : string {
        if ($this->getStock() > 5) {
            $msg = "In stock";
        } elseif ($this->getStock()> 0) {
            $msg = $this->getStock() . "left";
        } else {
            $msg = "Not available";
        }

        return $msg;
    }

}
