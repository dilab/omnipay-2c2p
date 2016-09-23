<?php
/**
 * Created by xu
 * Date: 22/9/16
 * Time: 11:43 AM
 */

namespace Omnipay\CreditCardPaymentProcessor\Message;


use Omnipay\Common\Message\AbstractRequest;

class RedirectCompletePurchaseRequest extends AbstractRequest
{
    public function getData()
    {
        $data = $this->httpRequest->request->all();

        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new RedirectCompletePurchaseResponse($this, $data);
    }

    //https://developer.2c2p.com/docs/redirect-variables
    private function hashValue()
    {

//        version +
//        request_timestamp +
//        merchant_id +
//        order_id +
//        invoice_no +
//        currency +
//        amount +
//        transaction_ref +
//        approval_code +
//        eci +
//        transaction_datetime +
//        payment_channel +
//        payment_status +
//        channel_response_code +
//        channel_response_desc +
//        masked_pan +
//        stored_card_unique_id +
//        backend_invoice +
//        paid_channel +
//        paid_agent +
//        recurring_unique_id +
//        user_defined_1 +
//        user_defined_2 +
//        user_defined_3 +
//        user_defined_4 +
//        user_defined_5 +
//        browser_info +
//        ippPeriod +
//        ippInterestType +
//        ippInterestRate +
//        ippMerchantAbsorbRate
    }
}