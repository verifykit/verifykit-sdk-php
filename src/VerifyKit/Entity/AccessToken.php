<?php

namespace VerifyKit\Entity;

/**
 * Class AccessToken
 * @package VerifyKit\Entity
 */
class AccessToken
{
    /** @var string */
    private $requestId;

    /** @var int */
    private $httpStatusCode;

    /** @var string */
    private $accessToken;

    /** @var \DateTime */
    private $timeout;

    /** @var string */
    private $errorCode;

    /** @var string */
    private $errorMessage;

    /** @var bool */
    private $success = false;

    /**
     * AccessToken constructor.
     * @param $response
     * @throws \Exception
     */
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
            $this->accessToken = isset($result["accessToken"]) ? $result["accessToken"] : null;
            $this->timeout = isset($result["timeout"]) ? new \DateTime($result["timeout"]) : null;
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
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return \DateTime
     */
    public function getTimeout()
    {
        return $this->timeout;
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


}