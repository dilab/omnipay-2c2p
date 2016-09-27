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
    private $data;

    public function setUp()
    {
        $this->data = [
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
    }

    public function testConstruct_payment_response_is_pending()
    {
        $this->data['payment_status'] = '001';

        $response = new RedirectCompletePurchaseResponse($this->getMockRequest(), $this->data);

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isPending());
        $this->assertFalse($response->isCancelled());
        $this->assertEquals('Pending (Waiting customer to pay)', $response->getMessage());
        $this->assertEquals('12345', $response->getTransactionReference());
        $this->assertSame(123456, $response->getTransactionId());
        $this->assertFalse($response->isTransparentRedirect());
    }

    public function testConstruct_payment_response_success()
    {
        $response = new RedirectCompletePurchaseResponse($this->getMockRequest(), $this->data);

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isPending());
        $this->assertFalse($response->isRedirect());
        $this->assertFalse($response->isCancelled());
        $this->assertEquals('Success using credit/debit card (Authorized) or Success when paid with cash channel (Paid)', $response->getMessage());
        $this->assertEquals('12345', $response->getTransactionReference());
        $this->assertSame(123456, $response->getTransactionId());
        $this->assertFalse($response->isTransparentRedirect());
    }

    public function testConstruct_payment_response_fail()
    {
        $this->data['payment_status'] = '002';

        $response = new RedirectCompletePurchaseResponse($this->getMockRequest(), $this->data);

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertFalse($response->isCancelled());
        $this->assertEquals('Rejected (Failed payment)', $response->getMessage());
        $this->assertEquals('12345', $response->getTransactionReference());
        $this->assertSame(123456, $response->getTransactionId());
        $this->assertFalse($response->isTransparentRedirect());
    }

    public function testConstruct_invalid_hash_value()
    {
        $this->data['computed_hash_value'] = '000000';

        $response = new RedirectCompletePurchaseResponse($this->getMockRequest(), $this->data);

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertFalse($response->isCancelled());
        $this->assertEquals('Invalid hash value', $response->getMessage());
        $this->assertEquals('12345', $response->getTransactionReference());
        $this->assertSame(123456, $response->getTransactionId());
        $this->assertFalse($response->isTransparentRedirect());
    }
}
