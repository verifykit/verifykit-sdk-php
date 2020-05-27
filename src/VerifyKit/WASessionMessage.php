<?php

namespace VerifyKit;

use VerifyKit\Entity\WAMessageResponse;
use VerifyKit\Exception\CurlException;
use VerifyKit\Exception\PhoneNumberEmptyException;
use VerifyKit\Exception\ServerKeyEmptyException;
use VerifyKit\Exception\TextMessageEmptyException;

/**
 * Class WASessionMessage
 * @classNo 837
 */
class WASessionMessage
{
    const URL = 'https://api.verifykit.com/v1.0';

    const METHOD_POST = 'POST';

    /** @var string */
    private $serverKey;

    /** @var string */
    private $clientIp = null;

    /**
     * WASessionMessage constructor.
     * @param $serverKey
     * @param null $clientIp
     * @throws ServerKeyEmptyException
     */
    public function __construct($serverKey, $clientIp = null)
    {
        if (null === $serverKey || $serverKey == "") {
            throw new ServerKeyEmptyException("Server key cannot be empty.", 837001);
        }
        $this->serverKey = $serverKey;

        if (!$clientIp) {
            $clientIp = $_SERVER["REMOTE_ADDR"];
        }
        $this->clientIp = $clientIp;
    }

    /**
     * @param $phoneNumber
     * @param $textMessage
     * @return WAMessageResponse
     * @throws CurlException
     * @throws PhoneNumberEmptyException
     * @throws TextMessageEmptyException
     */
    public function sendMessage($phoneNumber, $textMessage)
    {
        if (null === $phoneNumber || $phoneNumber == "") {
            throw new PhoneNumberEmptyException("Phone number cannot be empty.", 837002);
        }

        if (null === $textMessage || $textMessage == "" || strlen($textMessage) < 10) {
            throw new TextMessageEmptyException("Text message cannot be empty or less than 10 characters.", 837003);
        }

        $response = $this->makeRequest('/send-whatsapp-message', self::METHOD_POST,
            array("phoneNumber" => $phoneNumber, "message" => $textMessage)
        );

        return new WAMessageResponse($response);
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

        $userAgent = 'VerifyKitWAApiMessage/1.0.0';
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
}
