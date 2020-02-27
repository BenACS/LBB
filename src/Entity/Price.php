<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PriceRepository")
 */
class Price
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $priceDf;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="price")
     */
    private $price;

    public function __construct()
    {
        $this->price = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPriceDf(): ?float
    {
        return $this->priceDf;
    }

    public function setPriceDf(float $priceDf): self
    {
        $this->priceDf = $priceDf;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getPrice(): Collection
    {
        return $this->price;
    }

    public function addPrice(Product $price): self
    {
        if (!$this->price->contains($price)) {
            $this->price[] = $price;
            $price->setPrice($this);
        }

        return $this;
    }

    public function removePrice(Product $price): self
    {
        if ($this->price->contains($price)) {
            $this->price->removeElement($price);
            // set the owning side to null (unless already changed)
            if ($price->getPrice() === $this) {
                $price->setPrice(null);
            }
        }

        return $this;
    }
}
