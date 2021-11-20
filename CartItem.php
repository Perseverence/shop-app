<?php

declare(strict_types=1);

namespace App;

use JetBrains\PhpStorm\Pure;

class CartItem
{
    public int $quantity = 0;

    public function __construct(public Product $product, int $quantity)
    {
        $this->quantity = $quantity;
    }

    #[Pure]
    public function calculatePrice(): float
    {
        return $this->product->getPrice() * $this->quantity;
    }

    public function updateQuantity(int $quantity, string $action): void
    {
        if ($action === '+') {
            $this->quantity = $this->quantity + $quantity;
        } else if ($action === '-') {
            $this->quantity = $this->quantity - $quantity;
        }
    }
}