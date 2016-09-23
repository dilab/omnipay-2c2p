<?php
/**
 * Created by xu
 * Date: 22/9/16
 * Time: 11:18 AM
 */

namespace Omnipay\CreditCardPaymentProcessor\Message;


use Omnipay\Tests\TestCase;

class RedirectCompletePurchaseRequestTest extends TestCase
{

    /**
     * @var RedirectCompletePurchaseRequest
     */
    public $request;

    public function setUp()
    {
        $this->request = new RedirectCompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->getHttpRequest()->request->replace([
            'version' => '6.9',
            'request_timestamp' => '2015-09-17 10:30:12',
            'merchant_id' => 'merchant_test',
            'order_id' => '00000000000000123456',
            'invoice_no' => '00000000000000123456',
            'currency' => '764',
            'amount' => '000000010050',
            'transaction_ref' => '12345',
            'approval_code' => '103864',
            'transaction_datetime' => '2015-09-17 10:30:09',
            'payment_channel' => '001',
            'payment_status' => '000',
            'channel_response_code' => '000',
            'channel_response_desc' => 'Success',
            'masked_pan' => '444321XXXXXX3212',
            'user_defined_1' => '',
            'user_defined_2' => '',
            'user_defined_3' => '',
            'user_defined_4' => '',
            'user_defined_5' => '',
            'browser_info' => '',
            'eci' => '6',
            'hash_value' => '1234567',
        ]);

        $this->request->initialize([
            'card' => [
                'firstName' => 'Xu',
                'lastName' => 'Ding',
                'email' => 'xuding@spacebib.com',
                'number' => '93804194'
            ],
            'amount' => 199900,
            'currency' => 'JPY',
            'description' => 'Marina Run 2016',
            'transactionId' => 12,
            'returnUrl' => 'https://www.example.com/return',
            'cancelUrl' => 'https://www.example.com/cancel',
            'notifyUrl' => 'https://www.example.com/notify',
        ]);
    }

    public function testGetDataReturnCorrectComputedHashValue()
    {
        $data = $this->request->getData();

        $this->assertSame('a4THdPHQG9jT3DPZZ/mabkXUqow=', $data['computed_hash_value']);
    }

    //https://developer.2c2p.com/docs/redirect-variables
    private function hashValue()
    {

    }
}