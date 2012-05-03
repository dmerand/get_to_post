GET-TO-POST
===========
Author: _Donald L. Merand_ <http://donaldmerand.com>

Converts your GET requests into POST requests.

I wrote this because FileMaker doesn't do HTTP verbs other than GET. So if you want to be hip and use the intarwebs APIs and POST something programatically from FM, you need a proxy that can handle GETs but _secretly_ do POSTs.


Usage
-----
Send an SMS to 617.867.5309 using textbelt

1. Put get_to_post on your webserver
2. `http://yer-webserver/get_to_post.php?token=secret_string&url=http://textbelt.com/text&number=6178675309&message=hello`
3. The secret string in that is compared against $auth_tokens. Check the PHP file for my exceedingly advanced auth-token security. It's about 1% better than nothing.


License
-------
<http://www.opensource.org/licenses/MIT>

Also, don't blame me if this causes pain, sufferince, incontinence, etc. _Do_ give me credit if your company exits for 1 BEELYON dollars.
