<?php


namespace VerifyKit\Entity;

/**
 * Class Countries
 * @package VerifyKit\Entity
 */
class Countries
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

    /** @var array */
    private $countryList = array();

    /**
     * Countries constructor.
     * @param $response
     */
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

            foreach ($result["list"] as $item) {
                $country = new Country();
                $country->setPhoneCode($item["phoneCode"]);
                $country->setCountryCode($item["countryCode"]);
                $country->setTitle($item["title"]);

                $this->addListItem($country);
            }
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
     * @return array
     */
    public function getCountryList()
    {
        return $this->countryList;
    }

    /**
     * @param Country $country
     */
    public function addListItem(Country $country)
    {
        $this->countryList[] = $country;
    }


}
