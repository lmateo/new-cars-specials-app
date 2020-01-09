<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>

<?php
/*
require 'autoload.php';

define("AUTHORIZENET_API_LOGIN_ID", "9KDgMm2mw46V");
define("AUTHORIZENET_TRANSACTION_KEY", "5wek3X3DX5e39YAQ");
define("AUTHORIZENET_SANDBOX", true);

$subscription                          = new AuthorizeNet_Subscription;
$subscription->name                    = "PHP Monthly Magazine";
$subscription->intervalLength          = "1";
$subscription->intervalUnit            = "months";
$subscription->startDate               = "2015-04-22";
$subscription->totalOccurrences        = "999";
$subscription->amount                  = "12.99";
$subscription->creditCardCardNumber    = "6011000000000012";
$subscription->creditCardExpirationDate= "2018-10";
$subscription->creditCardCardCode      = "123";
$subscription->billToFirstName         = "Alex3";
$subscription->billToLastName          = "Doe";


$request         = new AuthorizeNetARB;
$response        = $request->createSubscription($subscription);
$subscription_id = $response->getSubscriptionId();



echo '<pre>' . print_r($response, true) . '</pre>';
*/
?>

</body>
</html>
<pre>
---- error ----

AuthorizeNetARB_Response Object
(
    [xml] => SimpleXMLElement Object
        (
            [messages] => SimpleXMLElement Object
                (
                    [resultCode] => Error
                    [message] => SimpleXMLElement Object
                        (
                            [code] => E00012
                            [text] => You have submitted a duplicate of Subscription 2391150. A duplicate subscription will not be created.
                        )

                )

        )

    [response] => ﻿ErrorE00012You have submitted a duplicate of Subscription 2391150. A duplicate subscription will not be created.
    [xpath_xml] => SimpleXMLElement Object
        (
            [messages] => SimpleXMLElement Object
                (
                    [resultCode] => Error
                    [message] => SimpleXMLElement Object
                        (
                            [code] => E00012
                            [text] => You have submitted a duplicate of Subscription 2391150. A duplicate subscription will not be created.
                        )

                )

        )

)
</pre>

<pre>
--- success ---

AuthorizeNetARB_Response Object
(
    [xml] => SimpleXMLElement Object
        (
            [messages] => SimpleXMLElement Object
                (
                    [resultCode] => Ok
                    [message] => SimpleXMLElement Object
                        (
                            [code] => I00001
                            [text] => Successful.
                        )

                )

            [subscriptionId] => 2391152
        )

    [response] => ﻿OkI00001Successful.2391152
    [xpath_xml] => SimpleXMLElement Object
        (
            [messages] => SimpleXMLElement Object
                (
                    [resultCode] => Ok
                    [message] => SimpleXMLElement Object
                        (
                            [code] => I00001
                            [text] => Successful.
                        )

                )

            [subscriptionId] => 2391152
        )

)
</pre>