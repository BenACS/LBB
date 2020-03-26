<?php

namespace App\Data\Cart;

use App\Entity\Account;

class CartData
{
    /**
     * @var Account
     */
    private $user;

    /**
     * @var integer
     */
    public $articleId;
    
    /**
     * @var null|integer
     */
    public $quantity;

    public function getUser() : ?Account
    {
        return $this->user;
    }

    public function setUser(Account $user): self
    {
        $this->user = $user;

        return $this;
    }
}