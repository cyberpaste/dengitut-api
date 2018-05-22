<?php

namespace UniteGroup;

class DengitutApi {

    private $apiKey = ''; /* Api ключ, получить можно здесь https://dengitut.online/manage/stores выбрав свой магазин */
    private $orderData = []; /* Товары в заказе */

    const API_URL = 'https://dengitut.online/api/request';
    const USER_ID = 'dt_userid';
    const PARTNER_ID = 'dt_partnerid';
    const DISCOUNT_ID = 'dt_discountid';
    const SHOP_ID = 'dt_shopid';
    const UTM_SOURCE = 'utm_source';
    const UTM_MEDIUM = 'utm_medium';
    const UTM_CAMPAIGN = 'utm_campaign';
    const UTM_CONTENT = 'utm_content';
    const ERROR_MESSAGE_API = 'Ошибка! Деньгитут - необходимо задать ключ API';

    public function __construct(string $apiKey) {
        if ($apiKey) {
            $this->apiKey = $apiKey;
        } else {
            throw new \Exception(self::ERROR_MESSAGE_API);
        }
        $this->userId = $this->getCookie(self::USER_ID);
        $this->partnerId = $this->getCookie(self::PARTNER_ID);
        $this->discountId = $this->getCookie(self::DISCOUNT_ID);
        $this->shopId = $this->getCookie(self::SHOP_ID);
        $this->utm_source = $this->getCookie(self::UTM_SOURCE);
        $this->utm_medium = $this->getCookie(self::UTM_MEDIUM);
        $this->utm_campaign = $this->getCookie(self::UTM_CAMPAIGN);
        $this->utm_content = $this->getCookie(self::UTM_CONTENT);
    }

    /*
     * @productName - Название товара, string
     * @productId - Id товара, int
     * @productPrice - Цена товара, float
     * @productQuantity - Количество товара, int
     * @productSku - Артикул товара, string
     */

    public function addToOrderData(string $productName, int $productId, float $productPrice, int $productQuantity, string $productSku) {
        $this->orderData[] = [
            'product_name' => $productName,
            'product_id' => $productId,
            'product_price' => $productPrice,
            'product_quantity' => $productQuantity,
            'product_sku' => $productSku,
        ];
    }

    /*
     * @userName - Имя пользователя, string
     * @userPhone - Телефон пользователя, string
     * @userEmail - Email пользователя, string
     * @orderId - Номер заказа, int
     * @orderTitle = Название заказа, string
     * @formName - Название Корзины, string
     */

    public function sendToApi(string $userName, string $userPhone, string $userEmail, int $orderId, string $orderTitle, string $formName) {
        if (isset($this->userId) && isset($this->shopId) && isset($this->discountId) && count($this->orderData) > 0) {
            $sendData = array(
                'action' => 'purchase',
                'api_key' => $this->apiKey,
                'id_user' => $this->userId,
                'user_name' => $userName,
                'user_phone' => $userPhone,
                'user_email' => $userEmail,
                'id_discount' => $this->discountId,
                'id_order' => $orderId,
                'title' => $orderTitle,
                'utm_source' => $this->utm_source,
                'utm_medium' => $this->utm_medium,
                'utm_campaign' => $this->utm_campaign,
                'utm_content' => $this->utm_content,
                'name_form' => $formName,
                'items' => json_encode($this->orderData),
            );
            $this->sendRequest($sendData);
        }
    }

    private function sendRequest($sendData) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_URL);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sendData);
        $result = curl_exec($ch);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    private function getCookie(string $cookieName) {
        if (isset($_GET[$cookieName]) && !isset($_COOKIE[$cookieName])) {
            setcookie($cookieName, $_GET[$cookieName], time() + (60 * 60 * 24 * 30), "/");
        }
        if (isset($_COOKIE[$cookieName])) {
            return $_COOKIE[$cookieName];
        }
        return false;
    }

    private function clearCookies() {
        $this->unsetCookie(self::USER_ID);
        $this->unsetCookie(self::PARTNER_ID);
        $this->unsetCookie(self::DISCOUNT_ID);
        $this->unsetCookie(self::SHOP_ID);
        $this->unsetCookie(self::UTM_SOURCE);
        $this->unsetCookie(self::UTM_MEDIUM);
        $this->unsetCookie(self::UTM_CAMPAIGN);
        $this->unsetCookie(self::UTM_CONTENT);
    }

    private function unsetCookie(string $cookieName) {
        if (isset($_COOKIE[$cookieName])) {
            setcookie($cookieName, "", time() - 3600);
        }
    }

    private function __get($key) {
        return $this->$key;
    }

    private function __set($key, $value) {
        $this->$key = $value;
    }

    private function __isset($key) {
        if (isset($this->$key)) {
            return true;
        }
        return false;
    }

}
