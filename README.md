# VerifyKit
![Status](https://img.shields.io/badge/Status-Beta-yellowgreen) ![License](https://img.shields.io/badge/License-MIT-red.svg)

VerifyKit is the next gen phone number validation system. Users can easily verify their  phone numbers without the need of entering phone number or a pin code.

*This SDK is a tool for you to use the [VerifyKit Rest API](https://github.com/verifykit/verifykit-sdk-php/blob/master/API.md) more easily.*

## Requirements

 - PHP >= 5.5
 - PHP-Curl
 - PHP-Json

## Installation

You can install via composer

```bash
composer require verifykit/verifykit-sdk-php
```

## Usage

### IMPORTANT NOTE

For the security of your application, you should send the IP address of the end user in all requests. To do this, you need to set $clientIp parameter in all class constructions.

#### Validation Method List

Firstly, get a list of validation methods.

```php
$vfk = new \VerifyKit\Web($serverKey, $clientIp);

/** @var \VerifyKit\Entity\ValidationMethodList $validationMethodList */
$validationMethodList = $vfk->getValidationMethodList();

/** @var \VerifyKit\Entity\ValidationMethod $validationMethod */
foreach ($validationMethodList->getList() as $validationMethod) {
    // $validationMethod-> getName, getApp, getText, getTextColour, getBgColour, getIcon...
}

// if you want to handle all localizations for validation steps, use this way.
/** @var \VerifyKit\Entity\Localization $localization */
foreach ($validationMethodList->getLocalizationList() as $localization){
    // getKey, getValue of localization.
}
```

#### Start Validation (WhatsApp or Telegram)

```php
$vfk = new \VerifyKit\Web($serverKey, $clientIp);

$validationMethod = 'whatsapp'; // or telegram. Required.

$lang = 'en'; // Language of end user. Default value is 'en' (English). This parameter is not required.   

// We can give both deeplink and qrCode in response to the start request. If you send qrCode parameter as (bool)true, you can see that you have received a base64 qrCode. By showing this qrCode to users coming from desktop browsers, you can make it easier to verify.
// We recommend that you do not send qrCode parameter as (bool)true for requests from mobile applications or mobile browsers. You should use deeplink for these platforms.
// This two parameters cannot be (bool)true at the same time. If you send both (bool)true at the same time, we will only give the deeplink in the response.
// Default value is true for deeplink, and false for qrCode. These parameters are not required.
$deeplink = true;
$qrCode = false;

/** @var \VerifyKit\Entity\ValidationStart $result */
$validationStart = $vfk->startValidation($validationMethod, $lang, $deeplink, $qrCode);

// if you want to redirect your user for validation, get deeplink.
echo $validationStart->getDeeplink();

// if you want to view a Qr code to your user for validation, get base64 png string and set it as an image source on web browsers.
echo $validationStart->getQrCode();

// keep this reference code for next step.
echo $validationStart->getReference();
```

#### Check Validation (WhatsApp or Telegram)

```php
$vfk = new \VerifyKit\Web($serverKey, $clientIp);

$reference = "111111"; // reference from startValidation step.

/** @var \VerifyKit\Entity\ValidationCheck $validation */
$validationCheck = $vfk->checkValidation($reference);
if ($validationCheck->getValidationStatus()) {
    $sessionId = $validationCheck->getSessionId(); // session id for the validation result
    $appPlatform = $validationCheck->getAppPlatform(); // web, android or ios
}
```

#### Start Validation (OTP)

##### Country List

We recommend that users should choose their country before typing their numbers in order to avoid confusion about the country code. For this reason, it may be helpful to get the country list before OTP verifications.

```php
$vfk = new \VerifyKit\Web($serverKey, $clientIp);

$countryCode = "TR"; // country code parameter for the request. We return the sent countryCode parameter at the top of the list in the response. If you want a specific country (user's country detected by ip on your side for example) to be the first response parameter, you can send $countryCode with your request. Not required.

$result = $vfk->getCountryList($countryCode);

/** @var \VerifyKit\Entity\Country $country */
foreach ($result->getCountryList() as $country){
    echo $country->getPhoneCode(); // phone code.
    echo $country->getCountryCode(); // country code
    echo $country->getTitle(); // country name
}

```

Then, start an OTP validation 

```php
$vfk = new \VerifyKit\Web($serverKey, $clientIp);

$phoneNumber = '+90........'; // End user phone number. Required.

$countryCode = 'TR'; // Country code of the end user's phone number. This parameter should exist in the country list request's response array as only the listed countries could be used for OTP validations. Required.

// For OTP verification to work best, you should send us the MCC and MNC code of the sim card in the user's device.
$mcc = '999'; // Mobile Country Code (MCC) of the sim card in the user's device. Default value is '999'. Not required.
$mnc = '999'; // Mobile Network Code (MNC) of the sim card in the user's device. Default value is '999'. Not required.

$lang = 'en'; // Language of end user. Default value is 'en' (English). You can set the language of the sent message. This parameter is not required.   


/** @var \VerifyKit\Entity\OTPSend $result */
$result = $vfk->sendOTP($phoneNumber, $countryCode, $mcc, $mnc, $lang);

$reference = $result->getReference(); // This parameter is required for a check OTP request.

```

#### Check Validation (OTP)

$phoneNumber, $countryCode, $reference, $code

```php
$vfk = new \VerifyKit\Web($serverKey, $clientIp);

$phoneNumber = '+90........'; // End user phone number. Required.

$countryCode = 'TR'; // Country code of the end user's phone number. This parameter should exist in the country list request's response array as only the listed countries could be used for OTP vadlidations. Required.

$reference = "111111"; // reference from sendOtp step. Required.

$code = "123456"; // The code to be entered by the user receiving the OTP.

/** @var \VerifyKit\Entity\OtpCheck $validation */
$otpCheck = $vfk->checkOtp($phoneNumber, $countryCode, $reference, $code);
if ($otpCheck->getValidationStatus()) {
    $sessionId = $otpCheck->getSessionId(); // session id for the OTP validation result
}
```

#### Complete Validation

Finally, get result by session id.

```php
$vfk = new \VerifyKit\VerifyKit($serverKey, $clientIp);

/** @var \VerifyKit\Entity\Response $result */
$result = $vfk->getResult($sessionId);

if ($result->isSuccess()) {
    echo "Phone number : " . $result->getPhoneNumber() .
        ", Validation Type : " . $result->getValidationType() .
        ", Validation Date : " . $result->getValidationDate()->format('Y-m-d H:i:s') . PHP_EOL;
} else {
    echo "Error message : " . $result->getErrorMessage() . ", error code : " . $result->getErrorCode() . PHP_EOL;
}
```


#### WhatsApp Session Message

If you want to send messages to your verified users within 24 hours with WhatsApp Session Message, you can use this way.
 
```php
$waMessage = new \VerifyKit\WASessionMessage($serverKey, $clientIp);


/** @var \VerifyKit\Entity\WAMessageResponse $result */
$result = $waMessage->sendMessage($phoneNumber, $textMessage); // Phone number that you received using the session id in the previous method.

if ($result->isSuccess()) {
    echo "Phone number : " . $result->getPhoneNumber() . ", Message : " . $result->getMessage() . ", Status : " . $result->getStatus() . PHP_EOL;
} else {
    echo "Error message : " . $result->getErrorMessage() . ", error code : " . $result->getErrorCode() . PHP_EOL;
}
```

#### Web SDK
If you want to use VerifyKit Web SDK, get an access token using unique id. For other details, [click here](https://github.com/verifykit/verifykit-sdk-php/blob/master/WebSDK.md).

```php
$vfk = new \VerifyKit\VerifyKit($serverKey, $clientIp);

/** @var \VerifyKit\Entity\AccessToken $result */
$result = $vfk->getWebAccessToken();

if ($result->isSuccess()) {
    echo "Access Token : " . $result->getAccessToken() .
        ", Timeout : " . $result->getTimeout()->format('Y-m-d H:i:s') . PHP_EOL;
} else {
    echo "Error message : " . $result->getErrorMessage() . ", error code : " . $result->getErrorCode() . PHP_EOL;
}
```


---

## Author

VerifyKit is owned and maintained by [VerifyKit DevTeam](mailto:sdk@verifykit.com).


## License

The MIT License

Copyright (c) 2019-2020 VerifyKit. [https://verifykit.com](https://verifykit.com)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.