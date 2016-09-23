<?php
namespace Omnipay\CreditCardPaymentProcessor\Message;


class RedirectPurchaseRequest extends AbstractRequest
{
    const VERSION = '6.9';

    public function getData()
    {
        $data = [
            'version' => self::VERSION,
            'merchant_id' => $this->getMerchantId(),
            'payment_description' => $this->getDescription(),
            'order_id' => $this->orderId(),
            'invoice_no' => $this->getInvoiceNo(),
            'amount' => $this->amount(),
            'currency' => $this->getCurrencyNumeric(),
            'hash_value' => $this->hashValue(),
            'result_url_1' => $this->getReturnUrl(),
            'result_url_2' => $this->getNotifyUrl(),
            'customer_email' => $this->getCard()->getEmail()
        ];

        return $data;
    }

    private function hashValue()
    {
        $strToHash = self::VERSION . $this->getMerchantId() . $this->getDescription() . $this->orderId() .
            $this->getInvoiceNo() . $this->getCurrency() . $this->amount() . $this->getCard()->getEmail() .
            $this->getReturnUrl() . $this->getNotifyUrl();

        return hash_hmac('sha1', $strToHash, $this->getSecretKey(), false);
    }

    private function amount()
    {
        return str_pad($this->getAmountInteger(), 12, '0', STR_PAD_LEFT);
    }

    private function orderId()
    {
        return str_pad($this->getTransactionId(), 12, '0', STR_PAD_LEFT);
    }

    public function sendData($data)
    {
        return $this->response = new RedirectPurchaseResponse($this, $data);
    }

}