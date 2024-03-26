<?php

namespace VerifyKit\Entity;

/**
 * Class Response
 * @package VerifyKit\Entity
 */
class Response
{
    /** @var string */
    private $requestId;

    /** @var int */
    private $httpStatusCode;

    /** @var string */
    private $validationType;

    /** @var \DateTime */
    private $validationDate;

    /** @var string */
    private $countryCode;

    /** @var string */
    private $phoneNumber;

    /** @var array */
    private $mobileNetwork;

    /** @var string */
    private $errorCode;

    /** @var string */
    private $errorMessage;

    /** @var bool */
    private $success = false;

    /**
     * Response constructor.
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
            $this->phoneNumber = isset($result["phoneNumber"]) ? $result["phoneNumber"] : null;
            $this->validationType = isset($result["validationType"]) ? $result["validationType"] : null;
            $this->validationDate = isset($result["validationDate"]) ? new \DateTime($result["validationDate"]) : null;
            $this->countryCode = isset($result["countryCode"]) ? $result["countryCode"] : null;
            $this->mobileNetwork = isset($result["mobileNetwork"]) ? $result["mobileNetwork"] : null;
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
     * @return string|null
     */
    public function getValidationType()
    {
        return $this->validationType;
    }

    /**
     * @return \DateTime|null
     */
    public function getValidationDate()
    {
        return $this->validationDate;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @return array|null
     */
    public function getMobileNetwork()
    {
        return $this->mobileNetwork;
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