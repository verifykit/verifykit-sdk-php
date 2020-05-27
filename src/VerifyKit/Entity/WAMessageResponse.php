<?php

namespace VerifyKit\Entity;


/**
 * Class WAMessageResponse
 * @package VerifyKit\Entity
 */
class WAMessageResponse
{
    /** @var string */
    private $requestId;

    /** @var int */
    private $httpStatusCode;

    /** @var string */
    private $message;

    /** @var \DateTime */
    private $status;

    /** @var string */
    private $phoneNumber;

    /** @var string */
    private $errorCode;

    /** @var string */
    private $errorMessage;

    /** @var bool */
    private $success = false;

    /**
     * WAMessageResponse constructor.
     * @param $response
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
            $this->phoneNumber = isset($result["phoneNumber"]) ? $result["phoneNumber"] : null;
            $this->message = isset($result["message"]) ? $result["message"] : null;
            $this->status = isset($result["status"]) ? $result["status"] : null;
        }
    }

    /**
     * @return string|null
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * @return int|null
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return \DateTime
     */
    public function getStatus()
    {
        return $this->status;
    }


    /**
     * @return string|null
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @return string|null
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }


    /**
     * @return string|null
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
