<?php

namespace MOST_MARKET;

/**
 * @see Documetations - coming soon
 */
class MOST_MARKETApi {
    /*
     * Методы используемые в API
     *
     *   const METHOD_GET = 'GET';
     *   const METHOD_PUT = 'PUT';
     *   const METHOD_PATCH = 'PATCH';
     *   const METHOD_DELETE = 'DELETE';
     *
     */

    const METHOD_POST = 'POST';

    /**
     * Получение купона
     *
     */
    public function GetCoupon(
    $key = null, $secret = null, $coupon_key = null
    ) {


        $parameters = [];
        $parameters['method'] = 'discountCouponExternal.Read';
        $string = time() * 1000;

        $sign = hash_hmac("sha512", $string, $secret, false);

        $parameters['params'] = array(
            'key' => $key,
            'sign' => $sign,
            'time' => time() * 1000,
            'where' => array(
                'key' => $coupon_key
            )
        );

        return $this->request($parameters, self::METHOD_POST);
    }

    /**
     * Получение купонов
     *
     */
    public function GetAllCoupon(
    $key = null, $secret = null, $get_all_coupon_last_time = null
    ) {


        $parameters = [];
        $parameters['method'] = 'discountCouponExternal.Read';
        $string = time() * 1000;

        $sign = hash_hmac("sha512", $string, $secret, false);

        $parameters['params'] = array(
            'key' => $key,
            'sign' => $sign,
            'time' => time() * 1000,
            'where' => array(
                'deprecation' => array(
                    '$gte' => time() * 1000
                ),
                'created' => array(
                    '$gte' => $get_all_coupon_last_time * 1000
                ),
                'used' => array(
                    '$exists' => false
                )
            )
        );

        return $this->request($parameters, self::METHOD_POST);
    }

    /**
     * Использование купона
     */
    public function UseCoupon(
    $key = null, $secret = null, $coupon_key = null
    ) {

        $parameters = [];
        $parameters['method'] = 'discountCouponExternal.Use';
        $string = time() * 1000 . ';' . $coupon_key;

        $sign = hash_hmac("sha512", $string, $secret, false);

        $parameters['params'] = array(
            'key' => $key,
            'sign' => $sign,
            'time' => time() * 1000,
            'item' => array(
                'key' => $coupon_key
            )
        );

        return $this->request($parameters, self::METHOD_POST);
    }

    /**
     * Получение параметров купонов
     */
    public function GetCouponsData(
    $key = null, $secret = null
    ) {

        $parameters = [];
        $parameters['method'] = 'discountCoupon.GetProvider';
        $string = time() * 1000;

        $sign = hash_hmac("sha512", $string, $secret, false);

        $parameters['params'] = array(
            'key' => $key,
            'sign' => $sign,
            'time' => time() * 1000
        );

        return $this->request($parameters, self::METHOD_POST);
    }

    /**
     * Отправка запроса
     *

     */
    protected function request($parameters = [], $method = 'POST', $headers = [], $timeout = 30) {
        $ch = curl_init();

        global $most_market_url;

        $url = $most_market_url["url"];

        if (count($parameters)) {

            $postdata = json_encode($parameters);

            if ($method === self::METHOD_POST) {
                curl_setopt($ch, CURLOPT_POST, true);

                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            }

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FAILONERROR, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_HEADER, 0);

            $response = curl_exec($ch);

            $info = curl_getinfo($ch);
            $errno = curl_errno($ch);
            $error = curl_error($ch);
            curl_close($ch);

            if ($errno) {
                throw new MOST_MARKETException('Error: ' . $error, $errno);
            }
            return json_decode($response, true);
        }
    }

}
