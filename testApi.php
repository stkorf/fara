<?php

ini_set("default_socket_timeout", 300);
ini_set('soap.wsdl_cache_enabled', '0');
ini_set('soap.wsdl_cache_ttl', '0');

class MagentoSoap {

    protected $_session;
    protected $_client;

    /**create Soap for Magento
     *
     * @param $url
     * @param $username
     * @param $apiKey
     */
    public function __construct($url, $username, $apiKey)
    {
        $urlLength = strlen($url) ;
        if($url[$urlLength -1] != '/') {
            $url .= '/';
        }
        if(!strpos($url, 'index.php')){
            $url .= 'index.php/';
        }
        $url .= 'api/v2_soap/?wsdl=1';

        $this->_client = new SoapClient($url);
        $this->_session = $this->_client->login($username, $apiKey);
        return $this;
    }

    /**get product from Magento by sky
     *
     * @param $sku
     * @return mixed
     */
    public function getProductBySku($sku)
    {
        $result = $this->_client->equipaymentGetProductInfo($this->_session, [
            'productid' => $sku,
            'websiteid' => 0, //0 is default store
        ]);
        $result = json_decode($result);
        return $result;
    }
}

//start use api
$soap = new MagentoSoap('http://staging.equipay.net/', 'equipay', 'a111111'); //for staging magento
//$soap = new MagentoSoap('http://magento.ce/', 'equipay', 'equipayequipay');

$product = $soap->getProductBySku('shw005'); //example of magento product sku: shw005 - configurable product, acj005 - simple product

// print product
echo $product->name.'<br>';
echo $product->sku.'<br>';
echo '<img src="'.$product->images->thumbnail.'">';

/*
echo '<pre>';
var_dump($product);
//*/