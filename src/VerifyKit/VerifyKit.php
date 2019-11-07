<?php

namespace VerifyKit;

use VerifyKit\entity\Response;
use VerifyKit\exception\CurlException;

/**
 * Class VerifyKit
 */
class VerifyKit
{

    const URL = 'https://api.verifykit.com/v1.0/result';

    /** @var string */
    private $serverKey;

    /**
     * VerifyKit constructor.
     * @param $serverKey
     */
    public function __construct($serverKey)
    {
        $this->serverKey = $serverKey;
    }

    /**
     * @param $sessionId
     * @return Response
     * @throws CurlException
     * @throws \Exception
     */
    public function getResult($sessionId)
    {

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
                "content-type: multipart/form-data;"
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

        $response = json_decode($response, true);
        $meta = $response["meta"];

        $vfkResponse = new Response();
        $vfkResponse->setRequestId($meta["requestId"]);
        $vfkResponse->setHttpStatusCode($meta["httpStatusCode"]);


        if (isset($meta["errorMessage"]) && isset($meta["errorCode"])) {
            $vfkResponse->setSuccess(false);
            $vfkResponse->setErrorCode($meta["errorCode"]);
            $vfkResponse->setErrorMessage($meta["errorMessage"]);
        } elseif (isset($response["result"])) {
            $result = $response["result"];
            $vfkResponse->setSuccess(true);
            $vfkResponse->setPhoneNumber($result["phoneNumber"]);
            $vfkResponse->setValidationType($result["validationType"]);
            $vfkResponse->setValidationDate((new \DateTime($result["validationDate"])));
        }

        return $vfkResponse;

    }
}

