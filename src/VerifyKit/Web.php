<?php

namespace VerifyKit;

use VerifyKit\Entity\ValidationCheck;
use VerifyKit\Entity\ValidationMethodList;
use VerifyKit\Entity\ValidationStart;
use VerifyKit\Exception\CurlException;
use VerifyKit\Exception\ReferenceEmptyException;
use VerifyKit\Exception\ServerKeyEmptyException;
use VerifyKit\Exception\ValidationMethodEmptyException;

/**
 * Class VerifyKit
 * @classNo 836
 */
class Web
{
    const URL = 'https://web-rest.verifykit.com/v1.0';

    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    /** @var string */
    private $serverKey;

    /** @var string */
    private $clientIp = null;

    /**
     * VerifyKit constructor.
     * @param $serverKey
     * @param $clientIp
     * @throws ServerKeyEmptyException
     */
    public function __construct($serverKey, $clientIp = null)
    {
        if (null === $serverKey || $serverKey == "") {
            throw new ServerKeyEmptyException("Server key cannot be empty.", 836001);
        }
        $this->serverKey = $serverKey;

        if (!$clientIp) {
            $clientIp = $_SERVER["REMOTE_ADDR"];
        }
        $this->clientIp = $clientIp;

    }


    /**
     * @throws CurlException
     */
    public function getValidationMethodList()
    {
        $response = $this->makeRequest('/init', self::METHOD_GET);

        return new ValidationMethodList($response);
    }


    /**
     * @param $validationMethod
     * @return ValidationStart
     * @throws CurlException
     * @throws ValidationMethodEmptyException
     */
    public function startValidation($validationMethod)
    {
        if (null === $validationMethod || $validationMethod == "") {
            throw new ValidationMethodEmptyException("Validation method cannot be empty.", 836002);
        }

        $response = $this->makeRequest('/start', self::METHOD_POST, array("app" => $validationMethod));

        return new ValidationStart($response);
    }

    /**
     * @param $reference
     * @return ValidationCheck
     * @throws CurlException
     * @throws ReferenceEmptyException
     */
    public function checkValidation($reference)
    {
        if (null === $reference || $reference == "") {
            throw new ReferenceEmptyException("Reference cannot be empty.", 836003);
        }

        $response = $this->makeRequest('/check', self::METHOD_POST, array('reference' => $reference));

        return new ValidationCheck($response);
    }

    /**
     * @param $endpoint
     * @param string $method
     * @param array $postFields
     * @return bool|string
     * @throws CurlException
     */
    protected function makeRequest($endpoint, $method = 'POST', $postFields = array())
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, self::URL . $endpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        $userAgent = 'VerifyKitWeb/1.0.0';
        if (isset($_SERVER["HTTP_USER_AGENT"])) {
            $userAgent .= ' - ' . $_SERVER["HTTP_USER_AGENT"];
        }
        curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "X-Vfk-Server-Key: " . $this->serverKey,
            "Cache-Control: no-cache",
            "Content-Type: application/json;",
            "X-Vfk-Forwarded-For: " . $this->clientIp
        ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postFields));
        }

        $response = curl_exec($curl);


        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        if (isset($error_msg)) {
            throw new CurlException($error_msg);
        }

        return $response;
    }
}