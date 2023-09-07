<?php

namespace VerifyKit;

use VerifyKit\Entity\BlacklistAdd;
use VerifyKit\Entity\BlacklistRemove;
use VerifyKit\Entity\Countries;
use VerifyKit\Entity\EmailValidationStart;
use VerifyKit\Entity\OTPCheck;
use VerifyKit\Entity\OTPSend;
use VerifyKit\Entity\ValidationCheck;
use VerifyKit\Entity\ValidationMethodList;
use VerifyKit\Entity\ValidationStart;
use VerifyKit\Exception\CountryCodeEmptyException;
use VerifyKit\Exception\CurlException;
use VerifyKit\Exception\OTPCodeEmptyException;
use VerifyKit\Exception\PhoneNumberEmptyException;
use VerifyKit\Exception\PhoneNumberListEmptyException;
use VerifyKit\Exception\ReferenceEmptyException;
use VerifyKit\Exception\ServerKeyEmptyException;
use VerifyKit\Exception\ValidationMethodEmptyException;
use VerifyKit\Exception\ValidationMethodNotValidException;

/**
 * Class VerifyKit
 * @classNo 836
 */
class Web
{
    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    /** @var string */
    private $serverKey;

    /** @var string */
    private $clientIp = null;

    /** @var string  */
    private $baseUrl = 'https://web-rest.verifykit.com/v1.0';

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
     * @param string $lang
     * @param bool $deeplink
     * @param bool $qrCode
     * @return ValidationStart
     * @throws CurlException
     * @throws ValidationMethodEmptyException
     */
    public function startValidation($validationMethod, $lang = 'en', $deeplink = true, $qrCode = false)
    {
        if (null === $validationMethod || $validationMethod == "") {
            throw new ValidationMethodEmptyException("Validation method cannot be empty.", 836002);
        }

        if (null === $lang) {
            $lang = 'en';
        }

        if (null === $deeplink) {
            $deeplink = true;
        }

        if (null === $qrCode) {
            $qrCode = true;
        }

        if (($deeplink && $qrCode) || (false === $deeplink && false === $qrCode)) {
            $deeplink = true;
            $qrCode = false;
        }

        $response = $this->makeRequest('/start', self::METHOD_POST,
            array("app" => $validationMethod, "lang" => $lang, "deeplink" => $deeplink, "qrCode" => $qrCode)
        );

        return new ValidationStart($response);
    }


    /**
     * @param string $countryCode
     * @param string $lang
     * @return Countries
     * @throws CurlException
     */
    public function getCountryList($countryCode = 'TR', $lang = 'en')
    {
        $response = $this->makeRequest('/country', self::METHOD_POST,
            array("lang" => $lang, "countryCode" => $countryCode)
        );

        return new Countries($response);
    }

    /**
     * @param $phoneNumber
     * @param $countryCode
     * @param int $mcc
     * @param int $mnc
     * @param string $lang
     * @return OTPSend
     * @throws CountryCodeEmptyException
     * @throws CurlException
     * @throws PhoneNumberEmptyException
     */
    public function sendOTP($phoneNumber, $countryCode, $mcc = 999, $mnc = 999, $lang = 'en')
    {
        if (null === $phoneNumber || $phoneNumber == "") {
            throw new PhoneNumberEmptyException("Phone number cannot be empty.", 836004);
        }

        if (null === $countryCode || $countryCode == "") {
            throw new CountryCodeEmptyException("Country code cannot be empty.", 836005);
        }

        if (null === $lang || $lang == '') {
            $lang = 'en';
        }

        $response = $this->makeRequest('/send-otp', self::METHOD_POST,
            array("phoneNumber" => $phoneNumber, "countryCode" => $countryCode, "mcc" => $mcc, "mnc" => $mnc, "lang" => $lang)
        );

        return new OTPSend($response);
    }

    /**
     * @param null $email
     * @return EmailValidationStart
     * @throws CurlException
     */
    public function startEmailValidation($email = null)
    {
        $postFields = array();
        if (null !== $email) {
            $postFields = array("email" => $email);
        }

        $response = $this->makeRequest('/start-email', self::METHOD_POST, $postFields);

        return new EmailValidationStart($response);
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
     * @param $phoneNumber
     * @param $countryCode
     * @param $reference
     * @param $code
     * @return OTPCheck
     * @throws CountryCodeEmptyException
     * @throws CurlException
     * @throws OTPCodeEmptyException
     * @throws PhoneNumberEmptyException
     * @throws ReferenceEmptyException
     */
    public function checkOTP($phoneNumber, $countryCode, $reference, $code)
    {
        if (null === $phoneNumber || $phoneNumber == "") {
            throw new PhoneNumberEmptyException("Phone number cannot be empty.", 836004);
        }

        if (null === $countryCode || $countryCode == "") {
            throw new CountryCodeEmptyException("Country code cannot be empty.", 836005);
        }

        if (null === $reference || $reference == "") {
            throw new ReferenceEmptyException("Reference cannot be empty.", 836006);
        }

        if (null === $code || $code == "") {
            throw new OTPCodeEmptyException("OTP Code cannot be empty.", 836007);
        }

        $response = $this->makeRequest('/check-otp', self::METHOD_POST,
            array("phoneNumber" => $phoneNumber, "countryCode" => $countryCode, "reference" => $reference, "code" => $code)
        );

        return new OTPCheck($response);
    }

    /**
     * @param $validationMethod
     * @param array $phoneNumberList
     * @return BlacklistAdd
     * @throws CurlException
     * @throws PhoneNumberListEmptyException
     * @throws ValidationMethodEmptyException
     * @throws ValidationMethodNotValidException
     */
    public function addPhoneNumbersToBlacklist($validationMethod, array $phoneNumberList = array())
    {
        if (null === $validationMethod || $validationMethod == "") {
            throw new ValidationMethodEmptyException("Validation method cannot be empty.", 836008);
        }

        if (false === in_array($validationMethod, Constant\ValidationMethod::getValidationMethods())) {
            throw new ValidationMethodNotValidException("Validation method is not valid.", 836012);
        }

        if (empty($phoneNumberList)) {
            throw new PhoneNumberListEmptyException("Validation method cannot be empty.", 836010);
        }

        $response = $this->makeRequest('/blacklist/add', self::METHOD_POST,
            array("type" => $validationMethod, "msisdn" => $phoneNumberList)
        );

        return new BlacklistAdd($response);
    }

    /**
     * @param $validationMethod
     * @param array $phoneNumberList
     * @return BlacklistRemove
     * @throws CurlException
     * @throws PhoneNumberListEmptyException
     * @throws ValidationMethodEmptyException
     * @throws ValidationMethodNotValidException
     */
    public function removePhoneNumbersFromBlacklist($validationMethod, array $phoneNumberList = array())
    {
        if (null === $validationMethod || $validationMethod == "") {
            throw new ValidationMethodEmptyException("Validation method cannot be empty.", 836009);
        }

        if (false === in_array($validationMethod, Constant\ValidationMethod::getValidationMethods())) {
            throw new ValidationMethodNotValidException("Validation method is not valid.", 836013);
        }

        if (empty($phoneNumberList)) {
            throw new PhoneNumberListEmptyException("Validation method cannot be empty.", 836011);
        }

        $response = $this->makeRequest('/blacklist/remove', self::METHOD_POST,
            array("type" => $validationMethod, "msisdn" => $phoneNumberList)
        );

        return new BlacklistRemove($response);
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

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

}