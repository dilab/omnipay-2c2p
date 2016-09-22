<?php
namespace Omnipay\CreditCardPaymentProcessor\Message;


use Omnipay\Common\Message\AbstractResponse;

class RedirectPurchaseResponse extends AbstractResponse
{
    private $endPointTest = 'https://demo2.2c2p.com/2C2PFrontEnd/RedirectV3/payment';
    private $endPointProduction = 'https://t.2c2p.com/RedirectV3/Payment';

    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return true;
    }

    public function isTransparentRedirect()
    {
        return true;
    }

    public function getTransactionId()
    {
        return $this->getData()['order_id'];
    }

    public function getRedirectUrl()
    {
        return $this->request->getTestMode() ? $this->endPointTest : $this->endPointProduction;
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        return $this->getData();
    }

}