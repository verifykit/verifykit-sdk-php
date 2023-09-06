<?php

namespace VerifyKit;

use VerifyKit\Entity\AccessToken;
use VerifyKit\Entity\Response;
use VerifyKit\Exception\CurlException;
use VerifyKit\Exception\ServerKeyEmptyException;
use VerifyKit\Exception\SessionIdEmptyException;

/**
 * Class VerifyKit
 * @classNo 835
 */
class VerifyKit
{

    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    /** @var string */
    private $serverKey;

    /** @var string */
    private $clientIp = null;

    /** @var string  */
    private $baseUrl = 'https://api.verifykit.com/v1.0';

    /**
     * VerifyKit constructor.
     * @param $serverKey
     * @param $clientIp
     * @throws ServerKeyEmptyException
     */
    public function __construct($serverKey, $clientIp = null)
    {
        if (null === $serverKey || $serverKey == "") {
            throw new ServerKeyEmptyException("Server key cannot be empty.", 835001);
        }
        $this->serverKey = $serverKey;

        if (!$clientIp) {
            $clientIp = $_SERVER["REMOTE_ADDR"];
        }
        $this->clientIp = $clientIp;
    }

    /**
     * @param $sessionId
     * @return Response
     * @throws CurlException
     * @throws \Exception
     */
    public function getResult($sessionId)
    {
        if (null === $sessionId || is_array($sessionId) || false == preg_match('/^[a-zA-Z0-9]+$/', $sessionId)) {
            throw new SessionIdEmptyException("Session id is empty or not formal: " . json_encode($sessionId), 835002);
        }

        $response = $this->makeRequest('/result', self::METHOD_POST, array("sessionId" => $sessionId));

        return new Response($response);
    }

    /**
     * @return AccessToken
     * @throws CurlException
     * @throws \Exception
     */
    public function getWebAccessToken()
    {
        $response = $this->makeRequest('/access-token', self::METHOD_GET);

        return new AccessToken($response);
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

        curl_setopt($curl, CURLOPT_URL, $this->baseUrl . $endpoint);
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
        if ($method == self::METHOD_POST) {
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

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

}
