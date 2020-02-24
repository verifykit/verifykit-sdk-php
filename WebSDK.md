Web Sdk Implementation
---
Web SDK works with an iframe structure that handles user authentication and returns session identification number on successful verification. Before using the web-sdk, customers should whitelist their site's domain by creating a web app and registering their domain as a **"trusted domain"** on [VerifyKit Dashboard](https://dashboard.verifykit.com)
 
* Before each authentication, developers should get a unique and one-time authentication token in order to initialize sdk script. (For details of this procedure please visit [here.](https://github.com/verifykit/verifykit-sdk-php#web-sdk))


After receiving the one-time token, javascript source and given div tag and should be inserted on the page where VerifyKit iframe is intended to appear. 
```
<div id="verifykit_iframe"></div>
<script type="text/javascript" src="https://widget.verifykit.com/v1.0/script.js?token={token}"></script>
```
* After including the script with received token parameter, **"initVerifyKit()"** method can be assigned to any login mechanism website owner prefers.
* When user successfully authenticates with VerifyKit, a MessageEvent will be triggered from the sdk, acknowledging the parent page of the successful verification and returning the obtained session identification number which can be used to fetch client detail.
* In order to receive the MessageEvent, developers should attach an EventListener. For example:
```bash
<script type="text/javascript">
    function log (evt) {
        console.log(SessionId: " + evt.data + " MessageEvent Origin: " + evt.origin);
        document.getElementById('verifykit_iframe').remove();
    }
    if (window.addEventListener) {
        window.addEventListener("message", log, false);
    }
    else {
        window.attachEvent("onmessage", log);
    }
</script>
```
* Developers can attach their javascript method to the listener instead of "log()" and receive session identification number with **"evt.data"**
* It is suggested to check the origin of MessageEvent with "evt.origin" value in order to verify the event sender is VerifyKit.
* After queuing session identification number, VerifyKit removes all html elements from the iframe, thus rendering the div tag empty. Developer can remove the "verifykit_iframe" div from their page in the event callback method as given in the code example.


