<?php

declare(strict_types=1);

use App\Product;
use App\CartItem;
use App\Cart;

require_once __DIR__ . '/../vendor/autoload.php';

$arrayProducts = [
    [
        'id' => 1,
        'name' => 'Phone',
        'price' => 250.00,
        'available_quantity' => 4
    ],
    [
        'id' => 2,
        'name' => 'Tablet',
        'price' => 200.00,
        'available_quantity' => 5
    ],
    [
        'id' => 3,
        'name' => 'TV',
        'price' => 350.00,
        'available_quantity' => 3
    ],
    [
        'id' => 4,
        'name' => 'Watch',
        'price' => 100.00,
        'available_quantity' => 5
    ],
];

$products = [];

foreach ($arrayProducts as $product) {
    $products[strtolower($product['name'])] = (new Product())->setId($product['id'])->setName($product['name'])->setPrice($product['price'])->setAvailableQuantity($product['available_quantity']);
}

$cart = new Cart();

$cart->add(3, new CartItem($products['phone']));
$cart->add(4, new CartItem($products['tablet']));
$cart->add(1, new CartItem($products['watch']));
$cart->remove(2, new CartItem($products['tablet']));
$cart->add(2, new CartItem($products['tv']));
$cart->remove(2, new CartItem($products['watch']));
$cart->add(2, new CartItem($products['phone']));


if ($cart->getProducts()) {
    foreach ($cart->getProducts() as $item) {
        echo 'Product name: ' . $item['name'] . ' quantity: (' . $item['quantity'] . ') Price: ' . $item['price'] . ' Total price: ' . $item['totalPrice'] . PHP_EOL;
    }
}

echo PHP_EOL . 'Total number of products in the cart: ' . $cart->getTotalCart() . PHP_EOL;
echo 'Discount: ' . $cart->getDiscount() . PHP_EOL;
echo 'Total price: ' . $cart->getTotalAmount() . PHP_EOL;


//echo "<pre>";
//print_r($cart->getProducts());
//echo "</pre>";

