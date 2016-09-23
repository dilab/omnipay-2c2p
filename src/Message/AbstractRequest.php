<?php
/**
 * Created by xu
 * Date: 22/9/16
 * Time: 2:16 PM
 */

namespace Omnipay\CreditCardPaymentProcessor\Message;


abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    const VERSION = '6.9';

    public function getInvoiceNo()
    {
        return $this->getParameter('invoiceNo');
    }

    public function setInvoiceNo($invoiceNo)
    {
        return $this->setParameter('invoiceNo', $invoiceNo);
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($merchantId)
    {
        return $this->setParameter('merchantId', $merchantId);
    }

    public function getSecretKey()
    {
        return $this->getParameter('secretKey');
    }

    public function setSecretKey($secretKey)
    {
        return $this->setParameter('secretKey', $secretKey);
    }

    public function getData()
    {
        $this->validate(
            'merchantId',
            'secretKey',
            'invoiceNo',
            'card',
            'amount',
            'currency',
            'description',
            'transactionId',
            'returnUrl',
            'notifyUrl'
        );
    }

    protected function emptyIfNotFound($haystack, $needle)
    {
        if (!isset($haystack[$needle])) {
            return '';
        }
        return $haystack[$needle];
    }
}