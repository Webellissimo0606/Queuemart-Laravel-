<?php

namespace App\Http\Controllers;


class PaymentGateway extends Controller
{
    public $merchantKey = null;
    public $merchantCode = null;
    private $paymentId  =   null;
    private $status  =   null;
    private $refNo  =   null;
    private $amount  =   null;
    private $currency  =   null;
    private $signatureType  =   "SHA256";
    private $signature  =   null;
    public $respondUrl = null;
    public $backendUrl = null;
    public $requestUrl = null;



    public function __construct()
    {
        $this->merchantCode = 'M13745'; //MerchantCode
        $this->merchantKey = '3eO46CjOg4'; //MerchantKey
        $this->requestUrl   =   'https://payment.ipay88.com.my/epayment/entry.asp'; // Request URL to ipay88 Server
        $this->respondUrl   =   'https://queuemart.me/response-iPay';
        $this->backendUrl   =   'https://queuemart.me/backend-iPay';
    }

    private function generateSignature($source) {
        //return base64_encode($this->hexString(sha1($source)));
        return hash($this->signatureType, $source);
    }

    public function setMerchantKey($data) {
        $this->merchantKey = $data;
        return $data;
    }

    public function setMerchantCode($data) {
        $this->merchantCode = $data;
        return $data;
    }

    public function setRefNo($data) {
        $this->refNo = substr($data, 0, 19);
        return $data;
    }
    /*
     * Set amount to be paid
     */
    public function setAmount($data) {
        $this->amount = number_format($data, 2);
        return $data;
    }
    /*
     * Set MYR only
     */
    public function setCurrency($data) {
        $this->currency = $data;
        return $data;
    }
    /*
         * Set payment Id
         */
    public function setPaymentId($data) {
        $this->paymentId = $data;
        return $data;
    }
    /*
         * Set payment Id
         */
    public function setPaymentStatus($data) {
        $this->status = $data;
        return $data;
    }

    public function setSignatureType($data) {
        $this->signatureType = $data;
        return $data;
    }
    /*
     * Set Return URL when user complete the process
     */
    public function setResponseURL($data) {
        $this->respondUrl = substr($data, 0, 199);
        return $data;
    }
    /*
     * Set Callback URL for server to server update
     */
    public function setBackendURL($data) {
        $this->backendUrl = substr($data, 0, 199);
        return $data;
    }
    /*
     *  Generate Signature
     */
    public function compileSignature() {
        $amount = preg_replace("/[^0-9]/", "", $this->amount);
        $this->signature = $this->generateSignature($this->merchantKey . $this->merchantCode . $this->refNo . $amount . $this->currency);
        return $this->signature;
    }

    public function verifySignature($receivedSignature) {
        $amount = preg_replace("/[^0-9]/", "", $this->amount);
        $string = $this->generateSignature($this->merchantKey . $this->merchantCode . $this->paymentId . $this->refNo . $amount . $this->currency . $this->status);
        if ($string == $receivedSignature) {
            return true;
        } else {
            return false;
        }
    }
}