API Implementation
---


### IMPORTANT NOTE

For the security of your application, you should send the IP address of the end user in all requests. To do this, you need to add X-Vfk-Forwarded-For header parameter in all requests.

#### Validation Method List

There are verification methods and language variables that you can use while preparing your verification screens. Response also includes specific warning messages in cases of errors for developers to act upon.


##### Example curl request

```bash
curl --request GET 'https://web-rest.verifykit.com/v1.0/init' \
--header 'X-Vfk-Server-Key: YOUR-SERVER-KEY' \
--header 'X-Vfk-Forwarded-For: END-USER-IP-ADDRESS' \
--header 'Content-Type: application/json'
```

##### Example response body

```json
{
    "meta": {
        "requestId": "REQUEST-ID",
        "httpStatusCode": 200
    },
    "result": {
        "list": [
            {
                "appPackage": "whatsapp",
                "name": "WhatsApp",
                "app": "whatsapp",
                "text": "Verify via WhatsApp",
                "textColour": "ffffff",
                "bgColour": "1bd741",
                "icon": "https://web-rest.verifykit.com/img/web/whatsapp@3x.png"
            },
            {
                "appPackage": "telegram",
                "name": "Telegram",
                "app": "telegram",
                "text": "Verify via Telegram",
                "textColour": "ffffff",
                "bgColour": "61a8de",
                "icon": "https://web-rest.verifykit.com/img/web/telegram@3x.png"
            },
            {
                "appPackage": "otp",
                "name": "Sms",
                "app": "otp",
                "text": "Click to verify via SMS",
                "textColour": "ffffff",
                "bgColour": "cbcbd0",
                "icon": "https://web-rest.verifykit.com/img/web/otp@3x.png"
            }
        ],
        "description": "Please tap on your preferred messaging app and send us the code appearing on the screen.",
        "localizationList": [
            {
                "key": "validation.description",
                "value": "Please tap on your preferred messaging app and send us the code appearing on the screen."
            },
            {
                "key": "validation.chooseAppText",
                "value": "Select a messaging app to verify your phone number."
            },
            .
            .
            .
        ],
        "messages": []
    }
}

```

If your SMS view selection setting is set to 'alternative' in your application detail screen on VerifyKit dashboard, SMS validation method parameters will be given in the list parameter as in the code snippet above. However, if your sms view selection setting is set to 'recommended' option, SMS validation method parameters will be as follows.

```json
{
    "alternativeValidation": "otp",
    "alternativeValidationDescription": "Don't use any of these apps?"
}
```



#### Start Validation (WhatsApp or Telegram)

When your users choose a validation method from the screen you prepare, you can start the validation process by sending the "app" parameter of selected validation method to this endpoint.
​
Three parameters are returned in the response. The first one is the "reference" code given to you in order to track the verification process on our end. The other two are the parameters you will use to verify your users. One of them is the qr code parameter which you can use if your users access your app through a browser. The other one is the deeplink to open the application in which your users will verify on their mobile devices.

##### Example curl request

```bash
curl  --request POST 'https://web-rest.verifykit.com/v1.0/start' \
--header 'X-Vfk-Server-Key: YOUR-SERVER-KEY' \
--header 'X-Vfk-Forwarded-For: END-USER-IP-ADDRESS' \
--header 'Content-Type: application/json' \
-d '{"app":"whatsapp"}'
```


Other parameters you can send at this request:
```bash
-d '{"lang":"en"}' # Language of end user. Default value is 'en' (English). This parameter is not required.

# We can give both deeplink and qrCode in response to the start request. If you send qrCode parameter as (bool)true, you can see that you have received a base64 qrCode. By showing this qrCode to users coming from desktop browsers, you can make it easier to verify.
# We recommend that you do not send qrCode parameter as (bool)true for requests from mobile applications or mobile browsers. You should use deeplink for these platforms.
# This two parameters cannot be (bool)true at the same time. If you send both (bool)true at the same time, we will only give the deeplink in the response.
# Default value is true for deeplink, and false for qrCode. These parameters are not required.
-d '{"deeplink":true}'
-d '{"qrCode":false}' 
```

##### Example response body

```json
{
    "meta": {
        "requestId": "REQUEST-ID",
        "httpStatusCode": 200
    },
    "result": {
        "deeplink": "https://wa.me/905395744034?text=.....",
        "qrCode": "data:image/png;base64,iVBORw0KGgoAA......",
        "reference": "111111"
    }
}
```

#### Check Validation (WhatsApp or Telegram)

With the "reference" code you received in the previous response, you can check whether the validation has been completed by the user or not.

If your user has completed the validation, you will receive a "session id" of this validation in the response.

##### Example curl request

```bash
curl  --request POST 'https://web-rest.verifykit.com/v1.0/check' \
--header 'X-Vfk-Server-Key: YOUR-SERVER-KEY' \
--header 'X-Vfk-Forwarded-For: END-USER-IP-ADDRESS' \
--header 'Content-Type: application/json' \
-d '{"reference":"REFERENCE-OF-VALIDATION"}'
```

##### Example response body

```json
{
    "meta": {
        "requestId": "REQUEST-ID",
        "httpStatusCode": 200
    },
    "result": {
        "validationStatus": true,
        "sessionId": "QWERTY123456"
    }
}
```

#### Start Validation (OTP)

##### Country List

Firstly, prepare a screen where your user will enter their phone number and country code. While preparing this screen, you can get the list of country information such as country code and phone code by sending a request to the "/country" endpoint like the example below.

##### Example curl request
```bash
curl  --request POST 'https://web-rest.verifykit.com/v1.0/country' \
--header 'Content-Type: application/json' \
--header 'X-Vfk-Forwarded-For: END-USER-IP-ADDRESS' \
--header 'X-Vfk-Server-Key: YOUR-SERVER-KEY' 
```

Other parameters you can send at this request:
```bash
-d '{"countryCode":"TR"}' country code parameter for the request. We return the sent countryCode parameter at the top of the list in the response. If you want a specific country (user's country detected by ip on your side for example) to be the first response parameter, you can send countryCode with your request. Not required.
```

##### Example response body

```json
{
    "meta": {
        "requestId": "REQUEST-ID",
        "httpStatusCode": 200
    },
    "result": {
        "list": [
            {
                "phoneCode": "string",
                "countryCode": "string",
                "title": "string"
            },
            .
            .
            .
         ]
    }
}
```


### Send Otp Request

Then, you must post the country code  and the phone number that your user entered. The response you receive includes the "reference" you will use for checking status and the validity period of this verification. During this period, a validation code will be sent to the phone number that was entered by your user. Proceed to the next step to continue verification with the user-entered code.


##### Example curl request
```bash
curl  --request POST 'https://web-rest.verifykit.com/v1.0/send-otp' \
--header 'X-Vfk-Server-Key: YOUR-SERVER-KEY' \
--header 'X-Vfk-Forwarded-For: END-USER-IP-ADDRESS' \
--header 'Content-Type: application/json' \
-d '{"phoneNumber":"PHONE_NUMBER","countryCode":"COUNTRY_CODE"}'
```

Other parameters you can send at this request:
```bash
#  For OTP verification to work best, you should send us the MCC and MNC code of the sim card in the user's device.
-d '{"mcc":"999"}' # Mobile Country Code (MCC) of the sim card in the user's device. Default value is '999'. Not required.
-d '{"mnc":"999"}' # Mobile Network Code (MNC) of the sim card in the user's device. Default value is '999'. Not required.

 -d '{"lang":"en"}' # Language of end user. Default value is 'en' (English). You can set the language of the sent message. This parameter is not required.   
```

##### Example response body
````json
{
    "meta": {
        "requestId": "REQUEST-ID",
        "httpStatusCode": 201
    },
    "result": {
        "reference": "123456",
        "timeout": 300
    }
}
````

#### Check Validation (OTP)


With the "reference" code you received in the previous response, you can check whether the validation has been completed by the user or not.

If your user has completed the validation, you will receive a "session id" of this validation in the response which means the verification process sucessfully finished.



##### Example curl request
```bash
curl  --request POST 'https://web-rest.verifykit.com/v1.0/check-otp' \
--header 'X-Vfk-Server-Key: YOUR-SERVER-KEY' \
--header 'X-Vfk-Forwarded-For: END-USER-IP-ADDRESS' \
--header 'Content-Type: application/json' \
-d '{"phoneNumber":"PHONE_NUMBER","countryCode":"COUNTRY_CODE","reference":"REFERENCE-OF-VALIDATION","code":"USER-ENTERED-CODE"}'
```

##### Example response body
```json
{
    "meta": {
        "requestId": "REQUEST-ID",
        "httpStatusCode": 200
    },
    "result": {
        "validationStatus": true,
        "sessionId": "QWERTY123456"
    }
}
```



### Last Step : Complete Validation

This is where you will get your user's credentials such as phone number et cetera. You can complete the validation by sending the "session id" parameter of the validation here.

##### Example curl request

```bash
curl  --request POST 'https://api.verifykit.com/v1.0/result' \
--header 'X-Vfk-Server-Key: YOUR-SERVER-KEY' \
--header 'X-Vfk-Forwarded-For: END-USER-IP-ADDRESS' \
--header 'Content-Type: application/json' \
-d '{"sessionId":"SESSION-ID-OF-VALIDATION"}'
```


##### Example response body

````json
{
    "meta": {
        "requestId": "REQUEST-ID",
        "httpStatusCode": 200
    },
    "result": {
        "validationType": "whatsapp",
        "validationDate": "Y-m-d H:i:s",
        "phoneNumber": "+9......",
        "countryCode": "TR"
    }
}
````

## Error Codes

If there are any errors, the response scheme you get will be as follows.

```json
{
    "meta": {
        "requestId": "REQUEST-ID",
        "httpStatusCode": "HTTP_STATUS_CODE",
        "errorMessage": "ERROR_MESSAGE",
        "errorCode": "ERROR_CODE"
    }
}
```

The list of error messages is as follows

|  HTTP Status Code 	|  Error Code 	|   Description	                                                        |
|---	                |---	        |---	                                                                |
| 400                   | 400007        | Invalid phone number, please check the phone number.                  |
| 403 	                | 403004	    | You must send either qrCode or deeplink parameter as true in order to start verification. |
| 403 	                | 403011	    | Validation type is not active.                                        |
| 403                   | 403012        | Phone number is banned.                                               |
| 403                   | 403013        | OTP Validation not found.                                             |   
| 403                   | 403014        | OTP code is invalid.                                                  |
| 403                   | 403015        | You have reached the limit of sending OTP code.                       |
| 403                   | 403036        | Validation not found.                                                 |
| 403                   | 403037        | Validation has expired.                                               |
| 403  	                | 403038        | Undefined application. Please check your credential parameters.       |
| 403 	                | 403041	    | You have reached the limit of package validation count.               |
| 403                   | 403042        | Please check your account balance on VerifyKit Dashboard.             |
| 403 	                | 403043	    | Please check your account balance on VerifyKit Dashboard.             |
| 403 	                | 403048	    | Email is invalid.                                                     |
| 403                   | 403047        | OTP setting is not active.                                            |
| 403                   | 403049        | OTP can only be used with test numbers.                               |
| 429 	                | 429001	    | Too many requests. please try again later.                            |
| 500	                | 500008 	    | Internal server error.                                                |