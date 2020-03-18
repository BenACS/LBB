<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdressRepository")
 * @ApiResource
 */
class Adress
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $zip;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $optionalInfo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $defaultAdress;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account", inversedBy="adresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getOptionalInfo(): ?string
    {
        return $this->optionalInfo;
    }

    public function setOptionalInfo(?string $optionalInfo): self
    {
        $this->optionalInfo = $optionalInfo;

        return $this;
    }

    public function getDefaultAdress(): ?bool
    {
        return $this->defaultAdress;
    }

    public function setDefaultAdress(bool $defaultAdress): self
    {
        $this->defaultAdress = $defaultAdress;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }
}
