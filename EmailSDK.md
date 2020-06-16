EmailSDK Implementation
---
### Start email validation.
Using this endpoint, you can start the email verification process. You need to send the email address of the user you want to verify as the required "email" parameter. In the response, you can get the reference and subject values of the verification for further status control purposes which will be explained later in this document. Example request and response values are given below.
##### Example curl request
```bash
curl  --request POST 'https://web-rest.verifykit.com/v1.0/start-email' \
--header 'X-Vfk-Server-Key: YOUR-SERVER-KEY' \
--header 'Content-Type: application/json' \
-d '{"email":"YOUR-CLIENT'S-EMAIL-ADDRESS"}'
```
##### Example response body
```json
{
    "meta": {
        "requestId": "REQUEST-ID",
        "httpStatusCode": 200
    },
    "result": {
        "reference": "REFERENCE-FOR-VERIFICATION",
        "to": "YOUR-EMAIL-ADDRESS",
        "subject": "verify-abc123abc123",
        "body": "BODY-FOR-EMAIL",
        "mailto": "mailto:YOUR-EMAIL-ADDRESS?subject=verify-abc123abc123&body=BODY-FOR-EMAIL",
    }
}
```
#### After starting the email verification process VerifyKit handles the verification process. You can check the process status or fetch the data of your user once the process completes successfully.
### Check if the validation is complete.
With the "reference" and "subject" value you received in the previous response, you can check whether the verification has been completed by the user or not. In this request you should send reference value as "reference" parameter and subject value as the "code" parameter in order to check the status of the verification.
If the verification is not completed yet, the response status code will be HTTP 403 with specific 403036 error code value which specifies the verification is still in progress.
##### Example curl request
```bash
curl  --request POST 'https://web-rest.verifykit.com/v1.0/check-email' \
--header 'X-Vfk-Server-Key: YOUR-SERVER-KEY' \
--header 'Content-Type: application/json' \
-d '{"reference":"REFERENCE-OF-VALIDATION", "code" : "SUBJECT VALUE OF START-EMAIL-REQUEST"}'
```
##### Example response body
```json
{
    "meta": {
        "requestId": "REQUEST-ID",
        "httpStatusCode": 200
    },
    "result": {
        "emailVerify" : "boolean",
        "dkimVerify" : "boolean",
        "spfVerify": "boolean",
        "email" : "VALIDATION-EMAIL-ADDRESS",
        "validationType" : "VALIDATION-TYPE",
        "validationDate" : "VALIDATION-DATE",
    }
}
```
##### Also after we receive the user mail from your side, we forward the user verification details to the callback url you provide. Example data structure for a response you will receive on your callback url is given below.
##### Example response body
```json
{
    "emailVerify" : "boolean",
    "dkimVerify" : "boolean",
    "spfVerify" : "boolean",
    "email" : "VALIDATION-EMAIL-ADDRESS",
    "validationType" : "VALIDATION-TYPE",
    "validationDate" : "VALIDATION-DATE",
}
```