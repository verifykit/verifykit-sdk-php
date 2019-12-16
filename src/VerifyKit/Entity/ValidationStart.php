<?php

namespace VerifyKit\Entity;

/**
 * Class ValidationStart
 * @package VerifyKit\Entity
 */
class ValidationStart
{
    /** @var string */
    private $requestId;

    /** @var int */
    private $httpStatusCode;

    /** @var string */
    private $errorCode;

    /** @var string */
    private $errorMessage;

    /** @var bool */
    private $success = false;

    /** @var string */
    private $deeplink;

    /** @var string */
    private $reference;

    /** @var string */
    private $qrCode;

    public function __construct($response)
    {
        $response = json_decode($response, true);
        $meta = $response["meta"];
        $this->requestId = (isset($meta["requestId"]) ? $meta["requestId"] : null);
        $this->httpStatusCode = (isset($meta["httpStatusCode"]) ? $meta["httpStatusCode"] : null);
        if (isset($meta["errorMessage"]) && isset($meta["errorCode"])) {
            $this->errorCode = $meta["errorCode"];
            $this->errorMessage = $meta["errorMessage"];
        } elseif (isset($response["result"])) {
            $result = $response["result"];
            $this->success = true;
            $this->deeplink = $result["deeplink"];
            $this->reference = $result["reference"];
            $this->qrCode = $result["qrCode"];
        }
    }

    /**
     * @return string
     */
    public function getRequestId()
    {
        return $this->requestId;
    }


    /**
     * @return int
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @return string
     */
    public function getDeeplink()
    {
        return $this->deeplink;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @return string
     */
    public function getQrCode()
    {
        return $this->qrCode;
    }

}