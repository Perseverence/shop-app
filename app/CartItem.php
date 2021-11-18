<?php

declare(strict_types=1);

namespace App;

class CartItem
{
    public function __construct(public Product $product)
    {
    }

    public function calculatePrice(int $quantity): float
    {
        return $this->product->getPrice() * $quantity;
    }
}