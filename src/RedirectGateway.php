<?php
namespace Omnipay\CreditCardPaymentProcessor;

use Omnipay\Common\AbstractGateway;

/**
 * 2c2p Gateway Driver for Omnipay
 *
 * This driver is based on
 * 2c2p Redirect API documentation
 * @link https://developer.2c2p.com/docs/redirect-api-red
 */
class RedirectGateway extends AbstractGateway
{

    public function getName()
    {
        return '2c2p';
    }

    public function getDefaultParameters()
    {
        return [
            'merchantId' => '',
            'secretKey' => '',
        ];
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\CreditCardPaymentProcessor\Message\RedirectPurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\CreditCardPaymentProcessor\Message\RedirectCompletePurchaseRequest', $parameters);
    }


}