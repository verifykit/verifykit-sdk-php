<?php

namespace VerifyKit\Entity;

/**
 * Class ValidationMethodList
 * @package VerifyKit\Entity
 */
class ValidationMethodList
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
    private $list = array();

    /** @var string */
    private $description;

    /** @var array */
    private $localizationList = array();

    /**
     * ValidationMethodList constructor.
     * @param $response
     */
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

            $this->setDescription($result["description"]);

            foreach ($result["list"] as $item) {
                $validationMethod = new ValidationMethod();
                $validationMethod->setName($item["name"]);
                $validationMethod->setApp($item["app"]);
                $validationMethod->setText($item["text"]);
                $validationMethod->setTextColour($item["textColour"]);
                $validationMethod->setBgColour($item["bgColour"]);
                $validationMethod->setIconPath($item["icon"]);

                $this->addListItem($validationMethod);
            }

            foreach ($result["localizationList"] as $localizationItem) {
                $localization = new Localization();
                $localization->setKey($localizationItem["key"]);
                $localization->setValue($localizationItem["value"]);

                $this->addLocalizationItem($localization);
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
     * @return array
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @param ValidationMethod $validationMethod
     */
    public function addListItem(ValidationMethod $validationMethod)
    {
        $this->list[] = $validationMethod;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return array
     */
    public function getLocalizationList()
    {
        return $this->localizationList;
    }

    /**
     * @param Localization $localization
     */
    public function addLocalizationItem(Localization $localization)
    {
        $this->localizationList[] = $localization;
    }


}