<?php
/**
 * This file aims to show you how to use this generated package.
 * In addition, the goal is to show which methods are available and the first needed parameter(s)
 * You have to use an associative array such as:
 * - the key must be a constant beginning with WSDL_ from AbstractSoapClientBase class (each generated ServiceType class extends this class)
 * - the value must be the corresponding key value (each option matches a {@link http://www.php.net/manual/en/soapclient.soapclient.php} option)
 * $options = [
 * WsdlToPhp\PackageBase\AbstractSoapClientBase::WSDL_URL => 'https://homologation.payline.com/V4/services/WebPaymentAPI\?wsdl',
 * WsdlToPhp\PackageBase\AbstractSoapClientBase::WSDL_TRACE => true,
 * WsdlToPhp\PackageBase\AbstractSoapClientBase::WSDL_LOGIN => 'you_secret_login',
 * WsdlToPhp\PackageBase\AbstractSoapClientBase::WSDL_PASSWORD => 'you_secret_password',
 * ];
 * etc...
 */

use Motherbrain\SyliusPaylinePlugin\Soap\ClassMap;

require_once __DIR__ . '/vendor/autoload.php';
/**
 * Minimal options
 */
$options = [
    WsdlToPhp\PackageBase\AbstractSoapClientBase::WSDL_URL => 'https://homologation.payline.com/V4/services/WebPaymentAPI\?wsdl',
    WsdlToPhp\PackageBase\AbstractSoapClientBase::WSDL_CLASSMAP => ClassMap::get(),
];
/**
 * Samples for Get ServiceType
 */
$get = new \Motherbrain\SyliusPaylinePlugin\Soap\ServiceType\Get($options);
/**
 * Sample call for getWebPaymentDetails operation/method
 */
if ($get->getWebPaymentDetails(new \Motherbrain\SyliusPaylinePlugin\Soap\StructType\GetWebPaymentDetailsRequest()) !== false) {
    print_r($get->getResult());
} else {
    print_r($get->getLastError());
}
/**
 * Sample call for getWebWallet operation/method
 */
if ($get->getWebWallet(new \Motherbrain\SyliusPaylinePlugin\Soap\StructType\GetWebWalletRequest()) !== false) {
    print_r($get->getResult());
} else {
    print_r($get->getLastError());
}
/**
 * Samples for Do ServiceType
 */
$do = new \Motherbrain\SyliusPaylinePlugin\Soap\ServiceType\_Do($options);
/**
 * Sample call for doWebPayment operation/method
 */
if ($do->doWebPayment(new \Motherbrain\SyliusPaylinePlugin\Soap\StructType\DoWebPaymentRequest()) !== false) {
    print_r($do->getResult());
} else {
    print_r($do->getLastError());
}
/**
 * Samples for Manage ServiceType
 */
$manage = new \Motherbrain\SyliusPaylinePlugin\Soap\ServiceType\Manage($options);
/**
 * Sample call for manageWebWallet operation/method
 */
if ($manage->manageWebWallet(new \Motherbrain\SyliusPaylinePlugin\Soap\StructType\ManageWebWalletRequest()) !== false) {
    print_r($manage->getResult());
} else {
    print_r($manage->getLastError());
}
/**
 * Samples for Create ServiceType
 */
$create = new \Motherbrain\SyliusPaylinePlugin\Soap\ServiceType\Create($options);
/**
 * Sample call for createWebWallet operation/method
 */
if ($create->createWebWallet(new \Motherbrain\SyliusPaylinePlugin\Soap\StructType\CreateWebWalletRequest()) !== false) {
    print_r($create->getResult());
} else {
    print_r($create->getLastError());
}
/**
 * Samples for Update ServiceType
 */
$update = new \Motherbrain\SyliusPaylinePlugin\Soap\ServiceType\Update($options);
/**
 * Sample call for updateWebWallet operation/method
 */
if ($update->updateWebWallet(new \Motherbrain\SyliusPaylinePlugin\Soap\StructType\UpdateWebWalletRequest()) !== false) {
    print_r($update->getResult());
} else {
    print_r($update->getLastError());
}
