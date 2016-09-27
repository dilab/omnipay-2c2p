<?php
/**
 * Created by xu
 * Date: 22/9/16
 * Time: 11:44 AM
 */

namespace Omnipay\CreditCardPaymentProcessor\Message;


use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class RedirectCompletePurchaseResponse extends AbstractResponse
{
    const SUCCESS_CODE = '000';

    const PENDING_CODE = '001';

    private $paymentStatusMessages = [
        '000' => 'Success using credit/debit card (Authorized) or Success when paid with cash channel (Paid)',
        '001' => 'Pending (Waiting customer to pay)',
        '002' => 'Rejected (Failed payment)',
        '003' => 'User cancel (Failed payment)',
        '999' => 'Error (Failed payment)',
        '1000' => 'Invalid hash value'
    ];

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        $this->data['payment_status'] = $this->data['payment_status'];

        if (strtoupper($this->data['hash_value']) != strtoupper($this->data['computed_hash_value'])) {
            $this->data['payment_status'] = '1000';
        }
    }

    public function isSuccessful()
    {
        return $this->data['payment_status'] == self::SUCCESS_CODE;
    }

    public function isPending()
    {
        return $this->data['payment_status'] == self::PENDING_CODE;
    }

    public function getMessage()
    {
        return $this->paymentStatusMessages[$this->data['payment_status']];
    }

    public function getTransactionReference()
    {
        return $this->data['transaction_ref'];
    }

    public function getTransactionId()
    {
        return intval($this->data['order_id']);
    }

}