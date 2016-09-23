<?php
namespace Omnipay\CreditCardPaymentProcessor;

use Omnipay\Tests\GatewayTestCase;

class RedirectGatewayTest extends GatewayTestCase
{
    /** @var RedirectGateway */
    protected $gateway;

    /** @var array */
    private $options;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new RedirectGateway($this->getHttpClient(), $this->getHttpRequest());

        $this->gateway->setMerchantId('merchant_123');

        $this->gateway->setSecretKey('secret_test');

        $this->options = [
            'card' => [
                'firstName' => 'Xu',
                'lastName' => 'Ding',
                'email' => 'xuding@spacebib.com',
                'number' => '93804194'
            ],
            'amount' => 1999.00,
            'currency' => 'THB',
            'description' => 'Marina Run 2016',
            'transactionId' => 12,
            'returnUrl' => 'https://www.example.com/return',
            'notifyUrl' => 'https://www.example.com/notify',
            'invoiceNo' => '20191212-123123'
        ];

    }

    public function testPurchase()
    {
        $this->gateway->setTestMode(false);
        $response = $this->gateway->purchase($this->options)->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('https://t.2c2p.com/RedirectV3/Payment', $response->getRedirectUrl());

        $this->gateway->setTestMode(true);
        $response = $this->gateway->purchase($this->options)->send();
        $this->assertEquals('https://demo2.2c2p.com/2C2PFrontEnd/RedirectV3/payment', $response->getRedirectUrl());
    }

    public function testCompletePurchase()
    {
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
            'hash_value' => 'B25609841046BD5E7C78912717A0CBB4DE2592EB'
        ]);

        $response = $this->gateway->completePurchase($this->options)->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertSame('12345', $response->getTransactionReference());
    }
}
