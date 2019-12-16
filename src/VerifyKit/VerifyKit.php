<?php

namespace VerifyKit;

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

    const URL = 'https://api.verifykit.com/v1.0/result';

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
        if (null === $sessionId || $sessionId == "") {
            throw new SessionIdEmptyException("Session id cannot be empty.", 835002);
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => self::URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array("sessionId" => $sessionId),
            CURLOPT_HTTPHEADER => array(
                "X-Vfk-Server-Key: " . $this->serverKey,
                "cache-control: no-cache",
                "content-type: multipart/form-data;",
                "X-Vfk-Forwarded-For: " . $this->clientIp
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        if (isset($error_msg)) {
            throw new CurlException($error_msg);
        }

        return new Response($response);
    }
}
