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


    public function testConstruct_ok()
    {
        $data = [
            'version' => '6.9',
            'merchant_id' => '874764000000130',
            'payment_description' => 'Marina Run 2015',
            'order_id' => 123,
            'invoice_no' => '',
            'amount' => '',
            'hash_value' => '',
            'customer_email' => 'xuding@spacebib.com'
        ];

        $request = $this->getMockRequest();
        $request->shouldReceive('getTestMode')->once()->andReturn(false);
        $response = new RedirectPurchaseResponse($request, $data);

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

    public function testConstruct_invalid_hash_value()
    {
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
