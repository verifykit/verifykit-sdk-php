<?php

namespace VerifyKit\Entity;

/**
 * Class BlacklistRemove
 * @package VerifyKit\Entity
 */
class BlacklistRemove
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

    /** @var int */
    private $totalCount = 0;

    /** @var int */
    private $successCount = 0;

    /** @var int */
    private $notExistsCount = 0;

    /** @var int */
    private $numberFormatCount = 0;

    /** @var int */
    private $failCount = 0;

    /** @var array */
    private $notExistsList = [];

    /** @var array */
    private $numberFormatList = [];

    /** @var array */
    private $failList = [];

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
            $this->totalCount = $result["totalCount"];
            $this->successCount = $result["successCount"];
            $this->notExistsCount = $result["notExistsCount"];
            $this->numberFormatCount = $result["numberFormatCount"];
            $this->failCount = $result["failCount"];
            $this->notExistsList = $result["notExistsList"];
            $this->numberFormatList = $result["numberFormatList"];
            $this->failList = $result["failList"];
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
     * @return int
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }


    /**
     * @return int
     */
    public function getSuccessCount()
    {
        return $this->successCount;
    }

    /**
     * @return int
     */
    public function getNotExistsCount()
    {
        return $this->notExistsCount;
    }


    /**
     * @return int
     */
    public function getNumberFormatCount()
    {
        return $this->numberFormatCount;
    }

    /**
     * @return int
     */
    public function getFailCount()
    {
        return $this->failCount;
    }

    /**
     * @return array
     */
    public function getNotExistsList()
    {
        return $this->notExistsList;
    }


    /**
     * @return array
     */
    public function getNumberFormatList()
    {
        return $this->numberFormatList;
    }

    /**
     * @return array
     */
    public function getFailList()
    {
        return $this->failList;
    }


}