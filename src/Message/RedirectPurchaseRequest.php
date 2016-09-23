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
            'result_url_1' => $this->getReturnUrl(),
            'result_url_2' => $this->getNotifyUrl(),
            'customer_email' => $this->getCard()->getEmail()
        ];

        $data['hash_value'] = $this->hashValue($data);

        return $data;
    }

    private function hashValue($data)
    {
        $strToHash =
            $this->emptyIfNotFound($data, 'version') .
            $this->emptyIfNotFound($data, 'merchant_id') .
            $this->emptyIfNotFound($data, 'payment_description') .
            $this->emptyIfNotFound($data, 'order_id') .
            $this->emptyIfNotFound($data, 'invoice_no') .
            $this->emptyIfNotFound($data, 'currency') .
            $this->emptyIfNotFound($data, 'amount') .
            $this->emptyIfNotFound($data, 'customer_email') .
            $this->emptyIfNotFound($data, 'pay_category_id') .
            $this->emptyIfNotFound($data, 'promotion') .
            $this->emptyIfNotFound($data, 'user_defined_1') .
            $this->emptyIfNotFound($data, 'user_defined_2') .
            $this->emptyIfNotFound($data, 'user_defined_3') .
            $this->emptyIfNotFound($data, 'user_defined_4') .
            $this->emptyIfNotFound($data, 'user_defined_5') .
            $this->emptyIfNotFound($data, 'result_url_1') .
            $this->emptyIfNotFound($data, 'result_url_2') .
            $this->emptyIfNotFound($data, 'enable_store_card') .
            $this->emptyIfNotFound($data, 'user_defined_5') .
            $this->emptyIfNotFound($data, 'pan_masked') .
            $this->emptyIfNotFound($data, 'request_3ds') .
            $this->emptyIfNotFound($data, 'recurring') .
            $this->emptyIfNotFound($data, 'order_prefix') .
            $this->emptyIfNotFound($data, 'recurring_amount') .
            $this->emptyIfNotFound($data, 'allow_accumulate') .
            $this->emptyIfNotFound($data, 'max_accumulate_amount') .
            $this->emptyIfNotFound($data, 'recurring_interval') .
            $this->emptyIfNotFound($data, 'recurring_count') .
            $this->emptyIfNotFound($data, 'charge_next_date') .
            $this->emptyIfNotFound($data, 'charge_on_date') .
            $this->emptyIfNotFound($data, 'payment_option') .
            $this->emptyIfNotFound($data, 'ipp_interest_type') .
            $this->emptyIfNotFound($data, 'payment_expiry') .
            $this->emptyIfNotFound($data, 'default_lang') .
            $this->emptyIfNotFound($data, 'statement_descriptor');

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