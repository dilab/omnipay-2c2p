<?php
namespace Omnipay\CreditCardPaymentProcessor\Message;


class RedirectPurchaseRequest extends AbstractRequest
{

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
//        version +
//        merchant_id +
//        payment_description +
//        order_id +
//        invoice_no +
//        currency +
//        amount +
//        customer_email +
//        pay_category_id +
//        promotion +
//        user_defined_1 +
//        user_defined_2 +
//        user_defined_3 +
//        user_defined_4 +
//        user_defined_5 +
//        result_url_1 +
//        result_url_2 +
//        enable_store_card +
//        stored_card_unique_id +
//        pan_masked +
//        request_3ds +
//        recurring +
//        order_prefix +
//        recurring_amount +
//        allow_accumulate +
//        max_accumulate_amount +
//        recurring_interval +
//        recurring_count +
//        charge_next_date +
//        charge_on_date +
//        payment_option +
//        ipp_interest_type +
//        payment_expiry +
//        default_lang +
//        statement_description

        $strToHash =
            self::VERSION .
            $this->getMerchantId() .
            $this->getDescription() .
            $this->orderId() .
            $this->getInvoiceNo() .
            $this->getCurrency() .
            $this->amount() .
            $this->getCard()->getEmail() .
            $this->getReturnUrl() .
            $this->getNotifyUrl();

        return strtoupper(hash_hmac('sha1', $strToHash, $this->getSecretKey(), false));
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