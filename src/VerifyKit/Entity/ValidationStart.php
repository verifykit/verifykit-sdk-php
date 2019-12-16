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
        $this->setRequestId(isset($meta["requestId"]) ? $meta["requestId"] : null);
        $this->setHttpStatusCode(isset($meta["httpStatusCode"]) ? $meta["httpStatusCode"] : null);
        if (isset($meta["errorMessage"]) && isset($meta["errorCode"])) {
            $this->setErrorCode($meta["errorCode"]);
            $this->setErrorMessage($meta["errorMessage"]);
        } elseif (isset($response["result"])) {
            $result = $response["result"];
            $this->setSuccess(true);
            $this->setDeeplink($result["deeplink"]);
            $this->setReference($result["reference"]);
            $this->setQrCode($result["qrCode"]);
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
     * @param string $requestId
     */
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
    }

    /**
     * @return int
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * @param int $httpStatusCode
     */
    public function setHttpStatusCode($httpStatusCode)
    {
        $this->httpStatusCode = $httpStatusCode;
    }

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param string $errorCode
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * @return string
     */
    public function getDeeplink()
    {
        return $this->deeplink;
    }

    /**
     * @param string $deeplink
     */
    public function setDeeplink($deeplink)
    {
        $this->deeplink = $deeplink;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return string
     */
    public function getQrCode()
    {
        return $this->qrCode;
    }

    /**
     * @param string $qrCode
     */
    public function setQrCode($qrCode)
    {
        $this->qrCode = $qrCode;
    }

}