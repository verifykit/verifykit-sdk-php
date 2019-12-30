# VerifyKit
![Status](https://img.shields.io/badge/Status-Beta-yellowgreen) ![License](https://img.shields.io/badge/License-MIT-red.svg)

VerifyKit is the next gen phone number validation system. Users can easily verify their  phone numbers without the need of entering phone number or a pin code.

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

Then, start a validation 

```php
$vfk = new \VerifyKit\Web($serverKey, $clientIp);

$validationMethod = 'whatsapp'; // or telegram

/** @var \VerifyKit\Entity\ValidationStart $result */
$validationStart = $vfk->startValidation($validationMethod);

// if you want to redirect your user for validation, get deeplink.
echo $validationStart->getDeeplink();

// if you want to view a Qr code to your user for validation, get base64 png string and view an image.
echo $validationStart->getQrCode();

// keep this reference code for next step.
echo $validationStart->getReference();
```

Then, check that validation is complete.

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


Finally, get result by session id.

```php
$vfk = new \VerifyKit\VerifyKit($serverKey);

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

#### Web SDK
If you want to use VerifyKit Web SDK, get an access token using unique id. For other details, [click here](https://github.com/verifykit/verifykit-sdk-php/blob/master/WebSDK.md).

```php
$vfk = new \VerifyKit\VerifyKit($serverKey);

/** @var \VerifyKit\Entity\AccessToken $result */
$result = $vfk->getAccessToken($uniqueId);

if ($result->isSuccess()) {
    echo "Access Token : " . $result->getAccessToken() .
        ", Unique Id : " . $result->getUniqueId() .
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