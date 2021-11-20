<?php

declare(strict_types=1);

ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');
error_reporting(E_ALL);

require_once __DIR__ . '\Product.php';
require_once __DIR__ . '\CartItem.php';
require_once __DIR__ . '\Cart.php';

use App\Product;
use App\CartItem;
use App\Cart;

class App
{
    const FILE_NAME = 'data';

    /**
     * @var array
     */
    private array $products = [];

    /**
     * @var array
     */
    private array $productsObjects = [];

    /**
     * @var array
     */
    private array $actions = [];

    /**
     * @var array
     */
    private array $discounts = [];

    /**
     * @var Cart
     */
    private Cart $cart;

    public function __construct()
    {
        $this->cart = new Cart();

        $this->parseContent();
    }

    private function parseContent(): void
    {
        $content = $this->getFileContent();

        if ($content) {
            $content = preg_split('/\n/', $content, -1);

            $identifiers = ['products', 'actions', 'discounts'];

            foreach ($identifiers as $identifier) {

                $keys = [];

                foreach ($content as $key => $row) {
                    if(str_contains($row, ucfirst($identifier))) {
                        $keys = $this->obtainColumns(ucfirst($identifier), $row);
                        unset($content[$key]);
                        continue;
                    }

                    if (empty($row)) {
                        unset($content[$key]);
                        break;
                    }

                    $this->{$identifier}[] = array_combine($keys, explode(' ', $row));
                    unset($content[$key]);
                }
            }

//            foreach ($content as $row) {
//                if(str_contains($row, 'Products')) {
//                    $position = 'products';
//                    $arrayKeys = explode(',', str_replace(array('Products', '(', ')', ' '), '', $row));
//                } else if(str_contains($row, 'Actions')) {
//                    $position = 'actions';
//                    $arrayKeys = explode(',', str_replace(array('Actions', '(', ')', ' '), '', $row));
//                } else if(str_contains($row, 'Discounts')) {
//                    $position = 'discounts';
//                    $arrayKeys = explode(',', str_replace(array('Discounts', '(', ')', ' '), '', $row));
//                } else {
//                    $this->{$position}[] = array_combine($arrayKeys, explode(' ', $row));
//                }
//            }
        }
    }

    /**
     * @param $identifier
     * @param $row
     * @return array
     */
    private function obtainColumns($identifier, $row): array
    {
        return explode(',', str_replace(array($identifier, '(', ')', ' '), '', $row));
    }

    /**
     * @return bool|string
     */
    private function getFileContent(): bool|string
    {
        if (self::FILE_NAME && file_exists(self::FILE_NAME . '.txt')) {
            return file_get_contents(__DIR__ . '/' . self::FILE_NAME . '.txt');
        } else {
            return false;
        }
    }

    public function createProductsObjects(): void
    {
        if (count($this->products) > 0) {
            foreach ($this->products as $product) {
                $this->productsObjects[(int) $product['id']] = (new Product())->setId((int) $product['id'])->setName($product['name'])->setPrice((float) $product['price'])->setAvailableQuantity((int) $product['available_quantity']);
            }
        }
    }

    public function simulateActions(): void
    {
        if (count($this->actions) > 0) {
            foreach ($this->actions as $action) {
                if (method_exists($this->cart, $action['action']) && is_callable(array($this->cart, $action['action']))) {
                    //echo 'Method called ' . $action['action'] . PHP_EOL;
                    call_user_func_array([$this->cart, $action['action']], [(int) $action['quantity'], $this->productsObjects[(int) $action['product_id']]]);
                }
            }
        }
    }

    public function getCartInfo(): void
    {
        if (!empty($this->cart->getProducts())) {
            foreach ($this->cart->getProducts() as $item) {
                echo 'Product name: ' . $item->product->getName() . ' quantity: (' . $item->quantity . ') Price: ' . $item->product->getPrice() . ' Total price: ' . $item->calculatePrice() . PHP_EOL;
            }

            echo PHP_EOL . 'Total number of products in the cart: ' . $this->cart->getTotalCart() . PHP_EOL;
            echo 'Discount: ' . $this->cart->getDiscount($this->discounts) . '%' . PHP_EOL;
            echo 'Total price: ' . $this->cart->getTotalAmount() . PHP_EOL;
        }
    }
}

$app = new App();
$app->createProductsObjects();
$app->simulateActions();
$app->getCartInfo();



//$arrayProducts = [
//    [
//        'id' => 1,
//        'name' => 'Phone',
//        'price' => 250.00,
//        'available_quantity' => 4
//    ],
//    [
//        'id' => 2,
//        'name' => 'Tablet',
//        'price' => 200.00,
//        'available_quantity' => 5
//    ],
//    [
//        'id' => 3,
//        'name' => 'TV',
//        'price' => 350.00,
//        'available_quantity' => 3
//    ],
//    [
//        'id' => 4,
//        'name' => 'Watch',
//        'price' => 100.00,
//        'available_quantity' => 5
//    ],
//];
//
//$products = [];
//
//foreach ($arrayProducts as $product) {
//    $products[strtolower($product['name'])] = (new Product())->setId($product['id'])->setName($product['name'])->setPrice($product['price'])->setAvailableQuantity($product['available_quantity']);
//}
//
//$cart = new Cart();
//
//$cart->add(3, new CartItem($products['phone']));
//$cart->add(4, new CartItem($products['tablet']));
//$cart->add(1, new CartItem($products['watch']));
//$cart->remove(2, new CartItem($products['tablet']));
//$cart->add(2, new CartItem($products['tv']));
//$cart->remove(2, new CartItem($products['watch']));
//$cart->add(2, new CartItem($products['phone']));
//
//
//if ($cart->getProducts()) {
//    foreach ($cart->getProducts() as $item) {
//        echo 'Product name: ' . $item['name'] . ' quantity: (' . $item['quantity'] . ') Price: ' . $item['price'] . ' Total price: ' . $item['totalPrice'] . PHP_EOL;
//    }
//}
//
//echo PHP_EOL . 'Total number of products in the cart: ' . $cart->getTotalCart() . PHP_EOL;
//echo 'Discount: ' . $cart->getDiscount() . PHP_EOL;
//echo 'Total price: ' . $cart->getTotalAmount() . PHP_EOL;


//echo "<pre>";
//print_r($cart->getProducts());
//echo "</pre>";

