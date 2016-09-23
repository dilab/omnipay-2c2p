<?php
/**
 * Created by xu
 * Date: 22/9/16
 * Time: 11:43 AM
 */

namespace Omnipay\CreditCardPaymentProcessor\Message;


class RedirectCompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        parent::getData();

        $data = $this->httpRequest->request->all();

        $data['computed_hash_value'] = $this->hashValue($data);

        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new RedirectCompletePurchaseResponse($this, $data);
    }

    private function hashValue($data)
    {
        $strToHash =
            $this->emptyIfNotFound($data, 'version') .
            $this->emptyIfNotFound($data, 'request_timestamp') .
            $this->emptyIfNotFound($data, 'merchant_id') .
            $this->emptyIfNotFound($data, 'order_id') .
            $this->emptyIfNotFound($data, 'invoice_no') .
            $this->emptyIfNotFound($data, 'currency') .
            $this->emptyIfNotFound($data, 'amount') .
            $this->emptyIfNotFound($data, 'transaction_ref') .
            $this->emptyIfNotFound($data, 'approval_code') .
            $this->emptyIfNotFound($data, 'eci') .
            $this->emptyIfNotFound($data, 'transaction_datetime') .
            $this->emptyIfNotFound($data, 'payment_channel') .
            $this->emptyIfNotFound($data, 'payment_status') .
            $this->emptyIfNotFound($data, 'channel_response_code') .
            $this->emptyIfNotFound($data, 'channel_response_desc') .
            $this->emptyIfNotFound($data, 'masked_pan') .
            $this->emptyIfNotFound($data, 'stored_card_unique_id') .
            $this->emptyIfNotFound($data, 'backend_invoice') .
            $this->emptyIfNotFound($data, 'paid_channel') .
            $this->emptyIfNotFound($data, 'paid_agent') .
            $this->emptyIfNotFound($data, 'recurring_unique_id') .
            $this->emptyIfNotFound($data, 'user_defined_1') .
            $this->emptyIfNotFound($data, 'user_defined_2') .
            $this->emptyIfNotFound($data, 'user_defined_3') .
            $this->emptyIfNotFound($data, 'user_defined_4') .
            $this->emptyIfNotFound($data, 'user_defined_5') .
            $this->emptyIfNotFound($data, 'browser_info') .
            $this->emptyIfNotFound($data, 'ippPeriod') .
            $this->emptyIfNotFound($data, 'ippInterestType') .
            $this->emptyIfNotFound($data, 'ippInterestRate') .
            $this->emptyIfNotFound($data, 'ippMerchantAbsorbRate');

        return strtoupper(hash_hmac('sha1', $strToHash, $this->getSecretKey(), false));

    }
}