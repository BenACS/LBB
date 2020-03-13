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
     * @ORM\ManyToMany(targetEntity="App\Entity\Image", mappedBy="article")
     */
    private $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        if(!$this->creationDate){
            $this->creationDate = new \DateTime();
        }
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

    public function getStockMessage(): string
    {
        if ($this->getStock() > 5) {
            $msg = '<span class="text-success">In stock</span>';
        } elseif ($this->getStock() > 0) {
            $msg = '<span class="text-warning"> Only ' . $this->getStock() . ' left</span>';
        } else {
            $msg = '<span class="text-danger">Not available</span>';
        }

        return $msg;
    }

    public function getArticleTitle(): string
    {
        $title = $this->getProduct()->getTitle();

        if ($this->getColor()) {
            $title .= ' - ' . ucfirst($this->getColor());
        }
        if ($this->getSize()) {
            $title .= ' - ' . $this->getSize();
        }
        if ($this->getDevice()) {
            $title .= ' - ' . $this->getDevice();
        }

        return $title;
    }

    public function __toString()
    {
        return $this->getArticleTitle();
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->addArticle($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            $image->removeArticle($this);
        }

        return $this;
    }
}
