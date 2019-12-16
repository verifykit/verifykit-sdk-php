<?php

namespace VerifyKit\Entity;

/**
 * Class ValidationCheck
 * @package VerifyKit\Entity
 */
class ValidationCheck
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

    /** @var bool */
    private $validationStatus;

    /** @var string */
    private $sessionId;

    /** @var string */
    private $appPlatform;

    public function __construct($response)
    {
        $response = json_decode($response, true);
        $meta = $response["meta"];
        $this->requestId = isset($meta["requestId"]) ? $meta["requestId"] : null;
        $this->httpStatusCode = isset($meta["httpStatusCode"]) ? $meta["httpStatusCode"] : null;
        if (isset($meta["errorMessage"]) && isset($meta["errorCode"])) {
            $this->errorCode = $meta["errorCode"];
            $this->errorMessage = $meta["errorMessage"];
        } elseif (isset($response["result"])) {
            $result = $response["result"];
            $this->success = true;
            $this->validationStatus = $result["validationStatus"];
            $this->sessionId = $result["sessionId"];
            $this->appPlatform = $result["appPlatform"];
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
     * @return bool
     */
    public function getValidationStatus()
    {
        return $this->validationStatus;
    }

    /**
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @return string
     */
    public function getAppPlatform()
    {
        return $this->appPlatform;
    }

}