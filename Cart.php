<?php

declare(strict_types=1);

namespace App;

class Cart
{
    private array $products = [];

    private float $totalAmount = 0;

    public function __construct()
    {
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function add(int $quantity, CartItem $cartItem): void
    {
        $inCart = $this->products[$cartItem->product->getId()] ?? null;

        if ($inCart === null) {
            $this->products[$cartItem->product->getId()] = [
                'name' => $cartItem->product->getName(),
                'quantity' => $quantity,
                'price' => $cartItem->product->getPrice(),
                'totalPrice' => $cartItem->calculatePrice($quantity)
            ];
        } else {
            $this->products[$cartItem->product->getId()]['quantity'] = $this->products[$cartItem->product->getId()]['quantity'] + $quantity;
            $this->products[$cartItem->product->getId()]['totalPrice'] = $cartItem->calculatePrice($this->products[$cartItem->product->getId()]['quantity']);
        }

        $this->calculateTotalAmount();
    }

    public function remove(int $quantity, CartItem $cartItem): void
    {
        $inCart = $this->products[$cartItem->product->getId()] ?? null;

        if ($inCart !== null) {
            if($quantity >= $this->products[$cartItem->product->getId()]['quantity']) {
                unset($this->products[$cartItem->product->getId()]);
            } else {
                $this->products[$cartItem->product->getId()]['quantity'] = $this->products[$cartItem->product->getId()]['quantity'] - $quantity;
            }
        }

        $this->calculateTotalAmount();
    }

    public function getTotalCart(): int
    {
        return count($this->products);
    }

    public function getTotalAmount(): float
    {
        $this->calculateTotalAmount();

        return $this->totalAmount;
    }

    private function calculateTotalAmount(): void
    {
        $amount = 0;

        if(isset($this->products)) {
            foreach ($this->products as $item) {
                $amount = $amount + $item['totalPrice'];
            }
        }

        $this->totalAmount = $amount;
    }

    public function getDiscount($discounts): float
    {
        return $this->totalAmount - ($this->totalAmount * ($this->calculateDiscount($discounts) / 100));
    }

    public function calculateDiscount($discounts): int
    {
        $percent = 0;

        foreach ($discounts as $discount) {
            if ($this->totalAmount <= $discount['min_price']) {
                $percent = $discount['percent'];
            }
        }

        return (int) $percent;
    }
}