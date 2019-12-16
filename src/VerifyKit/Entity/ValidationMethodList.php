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
        $this->requestId = (isset($meta["requestId"]) ? $meta["requestId"] : null);
        $this->httpStatusCode = (isset($meta["httpStatusCode"]) ? $meta["httpStatusCode"] : null);
        if (isset($meta["errorMessage"]) && isset($meta["errorCode"])) {
            $this->errorCode = $meta["errorCode"];
            $this->errorMessage = $meta["errorMessage"];
        } elseif (isset($response["result"])) {
            $result = $response["result"];
            $this->success = true;

            $this->description = $result["description"];

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