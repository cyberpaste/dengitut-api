# Dengitut-api
Библиотека для подключения магазина к партнерской программе https://dengitut.online/
## Пример использования
Переменная $cart содержит массив с товарами в заказе
```php
$dengitutApi = new \UniteGroup\DengitutApi('6b145f1d0c4d18f2e3ddafa9f33a8dc2');

/* корзина с вашими товарами */
$cart = [
    ['productName' => 'Тестовый товар', 'productId' => '22', 'productPrice' => '9999', 'productQuantity' => '2', 'productSku' => 'sku-2200'],
];

foreach ($cart as $cartItem) {
    $productName = $cartItem['productName'];
    $productId = $cartItem['productId'];
    $productPrice = $cartItem['productPrice'];
    $productQuantity = $cartItem['productQuantity'];
    $productSku = $cartItem['productSku'];

    $dengitutApi->addToOrderData($productName, $productId, $productPrice, $productQuantity, $productSku);
}

$userName = "Алексей";
$userPhone = "+74993330030";
$userEmail = "user@google.com";
$orderId = "22";
$orderTitle = "Заказа товара";
$formName = "Корзина";

$dengitutApi->sendToApi($userName, $userPhone, $userEmail, $orderId, $orderTitle, $formName);
```
## Помощь
По проблемам и вопросам по поводу интеграции, пишите на почту [info@dengitut.online](mailto:info@dengitut.online)
