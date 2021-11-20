<?php

declare(strict_types=1);

namespace App;

class Cart
{
    /**
     * @var array
     */
    private array $products = [];

    /**
     * @var float|int
     */
    private float $totalAmount = 0;

    /**
     * @var int
     */
    private int $discount = 0;

    public function __construct()
    {
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param int $quantity
     * @param Product $product
     */
    public function add(int $quantity, Product $product): void
    {
        $inCart = $this->products[$product->getId()] ?? null;

        if ($inCart === null) {
            $this->products[$product->getId()] = new CartItem($product, $quantity);
        } else {
            $this->products[$product->getId()]->updateQuantity($quantity, '+');
        }

//        if ($inCart === null) {
//            $this->products[$cartItem->product->getId()] = [
//                'name' => $cartItem->product->getName(),
//                'quantity' => $quantity,
//                'price' => $cartItem->product->getPrice(),
//                'totalPrice' => $cartItem->calculatePrice($quantity)
//            ];
//        } else {
//            $this->products[$cartItem->product->getId()]['quantity'] = $this->products[$cartItem->product->getId()]['quantity'] + $quantity;
//            $this->products[$cartItem->product->getId()]['totalPrice'] = $cartItem->calculatePrice($this->products[$cartItem->product->getId()]['quantity']);
//        }
        $this->calculateTotalAmount();
    }

    /**
     * @param int $quantity
     * @param Product $product
     */
    public function remove(int $quantity, Product $product): void
    {
        $inCart = $this->products[$product->getId()] ?? null;

        if ($inCart !== null) {
            if($quantity >= $this->products[$product->getId()]->quantity) {
                unset($this->products[$product->getId()]);
            } else {
                $this->products[$product->getId()]->updateQuantity($quantity, '-');
            }
        }
//        $inCart = $this->products[$cartItem->product->getId()] ?? null;
//
//        if ($inCart !== null) {
//            if($quantity >= $this->products[$cartItem->product->getId()]['quantity']) {
//                unset($this->products[$cartItem->product->getId()]);
//            } else {
//                $this->products[$cartItem->product->getId()]['quantity'] = $this->products[$cartItem->product->getId()]['quantity'] - $quantity;
//            }
//        }
//
       $this->calculateTotalAmount();
    }

    /**
     * @return int
     */
    public function getTotalCart(): int
    {
        return count($this->products);
    }

    /**
     * @return float
     */
    public function getTotalAmount(): float
    {
        $this->calculateTotalAmount();

        return $this->totalAmount;
    }

    private function calculateTotalAmount(): void
    {
        $amount = 0;

        if(isset($this->products)) {
            foreach ($this->products as $product) {
                $amount += $product->calculatePrice();
            }
        }

        $this->totalAmount = $this->discount !== 0 ? $amount - ($amount * ($this->discount / 100)) : $amount;
    }

    /**
     * @param $discounts
     * @return int
     */
    public function getDiscount($discounts): int
    {
        foreach ($discounts as $discount) {
            if ($this->totalAmount <= (int) $discount['min_price']) {
                $this->discount = (int) $discount['percent'];
                break;
            }
        }

        return $this->discount;
        //return $this->totalAmount - ($this->totalAmount * ($this->calculateDiscount($discounts) / 100));
    }

    /**
     * @param $discounts
     * @return int
     */
    public function calculateDiscount($discounts): int
    {
        foreach ($discounts as $discount) {
            if ($this->totalAmount <= (int) $discount['min_price']) {
                return (int) $discount['percent'];
            }
        }
    }
}