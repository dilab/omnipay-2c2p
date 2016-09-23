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

    private $dataPaymentResponse = [
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
        'hash_value' => '1234567'
    ];

    private $dataAlternativePaymentResponse = [
        'version' => '6.9',
        'request_timestamp' => '2016-03-01 15:28:22',
        'merchant_id' => 'merchant_123',
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
        'hash_value' => '1234567'
    ];

    /**
     * @var RedirectCompletePurchaseRequest
     */
    public $request;

    public function setUp()
    {
        $this->request = new RedirectCompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->request->initialize([
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
            'invoiceNo' => '20191212-123123',
            'merchantId' => 'merchant_123',
            'secretKey' => 'secret_test',
        ]);
    }

    public function testGetData()
    {
        $this->getHttpRequest()->request->replace($this->dataPaymentResponse);
        $data = $this->request->getData();
        $this->assertTrue(
            (array_intersect($this->dataPaymentResponse, $data) == $this->dataPaymentResponse)
        );
    }

    public function testSendData()
    {
        $this->getHttpRequest()->request->replace($this->dataPaymentResponse);
        $data = $this->request->getData();
        $this->assertInstanceOf(
            RedirectCompletePurchaseResponse::class,
            $this->request->sendData($data)
        );
    }

    public function testGetData_payment_request_computed_hash_value()
    {
        $strToHash =
            $this->dataPaymentResponse['version'] .
            $this->dataPaymentResponse['request_timestamp'] .
            $this->dataPaymentResponse['merchant_id'] .
            $this->dataPaymentResponse['order_id'] .
            $this->dataPaymentResponse['invoice_no'] .
            $this->dataPaymentResponse['currency'] .
            $this->dataPaymentResponse['amount'] .
            $this->dataPaymentResponse['transaction_ref'] .
            $this->dataPaymentResponse['approval_code'] .
            $this->dataPaymentResponse['eci'] .
            $this->dataPaymentResponse['transaction_datetime'] .
            $this->dataPaymentResponse['payment_channel'] .
            $this->dataPaymentResponse['payment_status'] .
            $this->dataPaymentResponse['channel_response_code'] .
            $this->dataPaymentResponse['channel_response_desc'] .
            $this->dataPaymentResponse['masked_pan'] .
            $this->dataPaymentResponse['user_defined_1'] .
            $this->dataPaymentResponse['user_defined_2'] .
            $this->dataPaymentResponse['user_defined_3'] .
            $this->dataPaymentResponse['user_defined_4'] .
            $this->dataPaymentResponse['user_defined_5'] .
            $this->dataPaymentResponse['browser_info'];

        $expected = strtoupper(hash_hmac('sha1', $strToHash, 'secret_test', false));

        $this->getHttpRequest()->request->replace($this->dataPaymentResponse);

        $data = $this->request->getData();

        $this->assertSame($expected, $data['computed_hash_value']);
    }

    public function testGetData_alternative_payment_computed_hash_value()
    {
        $strToHash =
            $this->dataAlternativePaymentResponse['version'] .
            $this->dataAlternativePaymentResponse['request_timestamp'] .
            $this->dataAlternativePaymentResponse['merchant_id'] .
            $this->dataAlternativePaymentResponse['order_id'] .
            $this->dataAlternativePaymentResponse['invoice_no'] .
            $this->dataAlternativePaymentResponse['currency'] .
            $this->dataAlternativePaymentResponse['amount'] .
            $this->dataAlternativePaymentResponse['transaction_ref'] .
            $this->dataAlternativePaymentResponse['approval_code'] .
            $this->dataAlternativePaymentResponse['eci'] .
            $this->dataAlternativePaymentResponse['transaction_datetime'] .
            $this->dataAlternativePaymentResponse['payment_channel'] .
            $this->dataAlternativePaymentResponse['payment_status'] .
            $this->dataAlternativePaymentResponse['channel_response_code'] .
            $this->dataAlternativePaymentResponse['channel_response_desc'] .
            $this->dataAlternativePaymentResponse['masked_pan'] .
            $this->dataAlternativePaymentResponse['backend_invoice'] .
            $this->dataAlternativePaymentResponse['user_defined_1'] .
            $this->dataAlternativePaymentResponse['user_defined_2'] .
            $this->dataAlternativePaymentResponse['user_defined_3'] .
            $this->dataAlternativePaymentResponse['user_defined_4'] .
            $this->dataAlternativePaymentResponse['user_defined_5'] .
            $this->dataAlternativePaymentResponse['browser_info'];


        $expected = strtoupper(hash_hmac('sha1', $strToHash, 'secret_test', false));

        $this->getHttpRequest()->request->replace($this->dataAlternativePaymentResponse);

        $data = $this->request->getData();

        $this->assertSame($expected, $data['computed_hash_value']);
    }


}