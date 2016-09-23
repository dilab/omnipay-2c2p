<?php
/**
 * Created by xu
 * Date: 22/9/16
 * Time: 10:09 AM
 */

namespace Omnipay\CreditCardPaymentProcessor\Message;

use Omnipay\Tests\TestCase;

class RedirectPurchaseRequestTest extends TestCase
{
    /**
     * @var RedirectPurchaseRequest
     */
    private $request;

    private $options;

    public function setUp()
    {
        $this->request = new RedirectPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->options = [
            'card' => [
                'email' => 'xuding@spacebib.com',
            ],
            'amount' => 10.00,
            'currency' => 'THB',
            'description' => 'Marina Run 2016',
            'transactionId' => 12,
            'returnUrl' => 'https://www.example.com/return',
            'notifyUrl' => 'https://www.example.com/notify',
            'invoiceNo' => '20191212-123123',
            'merchantId' => 'merchant_123',
            'secretKey' => 'secret_test',
        ];
    }

    public function testGetData()
    {
        $this->request->initialize($this->options);

        $result = $this->request->getData();

        $version = '6.9';
        $merchantId = 'merchant_123';
        $paymentDescription = 'Marina Run 2016';
        $orderId = '000000000012';
        $invoiceNo = '20191212-123123';
        $amount = '000000001000';
        $currency = '764';
        $resultUrl1 = 'https://www.example.com/return';
        $resultUrl2 = 'https://www.example.com/notify';
        $customerEmail = 'xuding@spacebib.com';

        $strSignatureString =
            $version .
            $merchantId .
            $paymentDescription .
            $orderId .
            $invoiceNo .
            $currency .
            $amount .
            $customerEmail .
            $resultUrl1 .
            $resultUrl2;

        $hashValue = strtoupper(hash_hmac('sha1', $strSignatureString, 'secret_test', false));

        $expected = [
            'version' => '6.9',
            'merchant_id' => 'merchant_123',
            'payment_description' => 'Marina Run 2016',
            'order_id' => '000000000012',
            'invoice_no' => '20191212-123123',
            'amount' => '000000001000',
            'currency' => '764',
            'hash_value' => $hashValue,
            'result_url_1' => 'https://www.example.com/return',
            'result_url_2' => 'https://www.example.com/notify',
            'customer_email' => 'xuding@spacebib.com'
        ];

        $this->assertEquals($expected, $result);
    }

    public function testGetData_currency()
    {
        $this->options = array_merge($this->options, [
            'amount' => 1999.00,
            'currency' => 'THB',
        ]);
        $this->request->initialize($this->options);
        $result = $this->request->getData();
        $this->assertEquals('000000199900', $result['amount']);


        $this->options = array_merge($this->options, [
            'amount' => 1999.00,
            'currency' => 'JPY',
        ]);
        $this->request->initialize($this->options);
        $result = $this->request->getData();
        $this->assertEquals('000000001999', $result['amount']);
    }

    public function testSendData()
    {
        $this->request->initialize($this->options);

        $this->assertInstanceOf(
            RedirectPurchaseResponse::class,
            $this->request->sendData($this->request->getData())
        );
    }
}
