Web Sdk Implementation
---
Web SDK works with an iframe structure that handles user authentication and returns session identification number on successful verification. Before using the web-sdk, customers should whitelist their site's domain by creating a web app and registering their domain as a **"trusted domain"** on [VerifyKit Dashboard](https://dashboard.verifykit.com)
* Before each authentication, developers should get a unique and one-time authentication token in order to initialize sdk script. (For details of this procedure please visit [here.](https://github.com/verifykit/verifykit-sdk-php#web-sdk))
After receiving the one-time token, javascript source and given div tag and should be inserted on the page where VerifyKit iframe is intended to appear. **lang** parameter determines the sdk screens' language (en, ru, tr, etc) and **token** parameter is needed for security and identification.
```html
<div id="verifykit_iframe"></div>
<script type="text/javascript" src="https://widget.verifykit.com/v2.1/script.js?lang={languageShortCode}&token={token}"></script>
```
* After inserting the code block above, a callback method **(cbMethod)** should be created on the parent page which should use the **sessionId** parameter that the identification value will be assigned when the verification successfully completes. This parameter should be stored and will be used to fetch client detail from backend to backend api request.
* After including the given code and creating the callback method, **"initVerifyKit(cbMethod)"** method can be assigned to any login mechanism website owner prefers. initVerifyKit method will initialize the iframe and set the callback method to the listener of the verification process.

```javascript
 let cbMethod = function(){
    console.log('Session id : ' + sessionId);
 }
 
 initVerifyKit(cbMethod);
```

* When user successfully authenticates with VerifyKit, user defined cbMethod will be triggered within the sdk scripts, running the intended business flow after the successful verification.
