<?php
/**
 * Created by xu
 * Date: 22/9/16
 * Time: 11:44 AM
 */

namespace Omnipay\CreditCardPaymentProcessor\Message;


use Omnipay\Tests\TestCase;

class RedirectCompletePurchaseResponseTest extends TestCase
{
    private $dataPaymentResponse;

    private $dataAlternativePaymentResponse;

    public function setUp()
    {
        $this->dataPaymentResponse = [
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
            'computed_hash_value' => '1234567'
        ];

        $this->dataAlternativePaymentResponse = [
            'version' => '6.9',
            'request_timestamp' => '2016-03-01 15:28:22',
            'merchant_id' => 'your merchant ID',
            'currency' => '764',
            'order_id' => '010316153510381',
            'amount' => '000000002500',
            'invoice_no' => '00000000000000123456',
            'transaction_ref' => '215061',
            'approval_code' => '',
            'eci' => '',
            'transaction_datetime' => '2016-03-01 15:28:49',
            'payment_channel' => '002',
            'payment_status' => '001',
            'channel_response_code' => '001',
            'channel_response_desc' => '',
            'masked_pan' => '',
            'stored_card_unique_id' => '',
            'backend_invoice' => '4723',
            'paid_channel' => '',
            'paid_agent' => '',
            'user_defined_1' => '',
            'user_defined_2' => '',
            'user_defined_3' => '',
            'user_defined_4' => '',
            'user_defined_5' => '',
            'browser_info' => 'Type=Firefox44,Name=Firefox,Ver=44.0',
            'hash_value' => '',
            'computed_hash_value' => '1234567'
        ];
    }

    public function testConstruct_payment_response_success()
    {
        $response = new RedirectCompletePurchaseResponse($this->getMockRequest(), $this->dataPaymentResponse);

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertFalse($response->isCancelled());
        $this->assertEquals('Success using credit/debit card (Authorized) or Success when paid with cash channel (Paid)', $response->getMessage());
        $this->assertEquals('12345', $response->getTransactionReference());
        $this->assertSame(123456, $response->getTransactionId());
        $this->assertFalse($response->isTransparentRedirect());
    }

    public function testConstruct_payment_response_fail()
    {
        $this->dataPaymentResponse['payment_status'] = '002';

        $response = new RedirectCompletePurchaseResponse($this->getMockRequest(), $this->dataPaymentResponse);

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertFalse($response->isCancelled());
        $this->assertEquals('Rejected (Failed payment)', $response->getMessage());
        $this->assertEquals('12345', $response->getTransactionReference());
        $this->assertSame(123456, $response->getTransactionId());
        $this->assertFalse($response->isTransparentRedirect());
    }

    public function testConstruct_payment_response_has_not_match()
    {
        $this->dataPaymentResponse['computed_hash_value'] = '000000';

        $response = new RedirectCompletePurchaseResponse($this->getMockRequest(), $this->dataPaymentResponse);

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertFalse($response->isCancelled());
        $this->assertEquals('Invalid hash value', $response->getMessage());
        $this->assertEquals('12345', $response->getTransactionReference());
        $this->assertSame(123456, $response->getTransactionId());
        $this->assertFalse($response->isTransparentRedirect());
    }

    public function testConstruct_invalid_hash_value()
    {
        $this->markTestSkipped();
        $data = [
            'version' => '',
            'merchant_id' => '',
            'payment_description' => '',
            'order_id' => 123,
            'invoice_no' => '',
            'amount' => '',
            'hash_value' => '',
            'customer_email' => 'xuding@spacebib.com'
        ];

        $response = new RedirectPurchaseResponse($this->getMockRequest(), $data);

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertFalse($response->isCancelled());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getTransactionReference());
        $this->assertTrue($response->isTransparentRedirect());
        $this->assertSame(123, $response->getTransactionId());
        $this->assertSame('https://t.2c2p.com/RedirectV3/Payment', $response->getRedirectUrl());
        $this->assertSame('POST', $response->getRedirectMethod());
        $this->assertEquals($data, $response->getRedirectData());
    }

    public function testConstruct_payment_status_no_success()
    {
        $this->markTestSkipped();

        $data = [
            'version' => '',
            'merchant_id' => '',
            'payment_description' => '',
            'order_id' => 123,
            'invoice_no' => '',
            'amount' => '',
            'hash_value' => '',
            'customer_email' => 'xuding@spacebib.com'
        ];

        $response = new RedirectPurchaseResponse($this->getMockRequest(), $data);

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertFalse($response->isCancelled());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getTransactionReference());
        $this->assertTrue($response->isTransparentRedirect());
        $this->assertSame(123, $response->getTransactionId());
        $this->assertSame('https://t.2c2p.com/RedirectV3/Payment', $response->getRedirectUrl());
        $this->assertSame('POST', $response->getRedirectMethod());
        $this->assertEquals($data, $response->getRedirectData());
    }
}
