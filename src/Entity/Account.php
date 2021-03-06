<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccountRepository")
 * @UniqueEntity(
 *  fields = {"email"},
 *  message= "This email is already used")
 * @ApiResource
 */
class Account implements UserInterface
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
    private $lastname;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Your password must contain 8 characters")
     * @Assert\EqualTo(propertyPath="confirmPassword",message="Your password doesn't match")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="confirmPassword",message="Your password doesn't match")
     */
    public $confirmPassword;
    /**
     * @ORM\Column(type="date")
     */
    private $birthdate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $newsletter;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registerDate;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $accountType;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $siret;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $codeTva;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="account", orphanRemoval=true)
     */
    private $reviews;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Orders", mappedBy="account")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Adress", mappedBy="account", orphanRemoval=true)
     */
    private $adresses;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $roles;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->adresses = new ArrayCollection();
        if (!$this->registerDate) {
            $this->registerDate = new \DateTime();
        }
        if (!$this->roles) {
            $this->roles[] = 'ROLE_USER';
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getNewsletter(): ?bool
    {
        return $this->newsletter;
    }

    public function setNewsletter(bool $newsletter): self
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    public function getRegisterDate(): ?\DateTimeInterface
    {
        return $this->registerDate;
    }

    public function setRegisterDate(\DateTimeInterface $registerDate): self
    {
        $this->registerDate = $registerDate;

        return $this;
    }

    public function getAccountType(): ?string
    {
        return $this->accountType;
    }

    public function setAccountType(string $accountType): self
    {
        $this->accountType = $accountType;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getCodeTva(): ?string
    {
        return $this->codeTva;
    }

    public function setCodeTva(?string $codeTva): self
    {
        $this->codeTva = $codeTva;

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
            $review->setAccount($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getAccount() === $this) {
                $review->setAccount(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Orders[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Orders $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setAccount($this);
        }

        return $this;
    }

    public function removeOrder(Orders $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getAccount() === $this) {
                $order->setAccount(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Adress[]
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addAdress(Adress $adress): self
    {
        if (!$this->adresses->contains($adress)) {
            $this->adresses[] = $adress;
            $adress->setAccount($this);
        }

        return $this;
    }

    public function removeAdress(Adress $adress): self
    {
        if ($this->adresses->contains($adress)) {
            $this->adresses->removeElement($adress);
            // set the owning side to null (unless already changed)
            if ($adress->getAccount() === $this) {
                $adress->setAccount(null);
            }
        }

        return $this;
    }

    public function setRoles(?array $roles): self
    {

        $this->roles = $roles;

        return $this;
    }
    public function eraseCredentials()
    {
    }

    public function getSalt()
    {
    }


    public function getRoles(): ?array
    {

        $roles = $this->roles;

        return $this->roles;
    }

    public function getUsername()
    {

        return $this->email;
    }

    public function __toString()
    {
        return $this->email;
    }
}
