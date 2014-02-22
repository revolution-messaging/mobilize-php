#About the Mobilize Library

PHP client library for the Mobilize SMS application from Revolution Messaging, LLC.
This library consists of two classes: MobilizeAPI, a general-purpose class for interacting with the Mobilize messaging API, and MobilizeHook, for the creation of PHP scripts designed to work with Mobilize's web hooks. These two classes are often used together, though neither depends on the other.

##The MobilizeHook Class

This class allows for easy access to the Mobilize webhooks which appear in:

* Dynamic Content message flow components
* Post-to-URL message flow components
* Metadata update hooks

###Properties

* `endSession (boolean, defaults to true)`: when outputting a dynamic content object, this value tells Mobilize whether to move on to the next messaging component (true) or to await further input from the user (false).
* `response (string)`: the message that will be sent back to the user when placed in a dynamic content object. Note that response may be any length, but the message will be truncated at the last whitespace before the message reaches the responseLength property.
* `format (xml|json)`: this tells the object what format to expect in the payload and how to output dynamic content objects.
* `method (get|post)`: this tells the object where to look for inputs, either in the URL string or in the request body.
* `responseLength`: the length of response allowed in SMS messages sent from the hook. Defaults to the US standard of 160.
* `inputs (array)`: an array of all values provided by Mobilize

###Instantiation

Instantiation function: `new MobilizeHook(string $format=*xml|json, string $method=*post|get, bool $retrieve=*true|false, int $responseLength=*160);
All arguments are optional; $format, $method and $responseLength set the respective property of the hook instance. $retrieve determines whether the hook will attempt to retrieve user inputs upon instantiation.

###Using Mobilize Data

Once the class is instantiated, the MobilizeHook object should contain all data delivered by Mobilize in the `inputs` property. Mobilize will always send a request body with the full range of inputs, though not all will be used with all hooks, and by setting `method` to 'get' it is possible to have the MobilizeHook object only look for message data in the URL string.

Dynamic Content and Post-to-URL hooks will have values set in the request body for the following variables:

* `msisdn`: the user's mobile phone number. Typically this will be in 12-digit format (the ten-digit number preceded by 01).
* `mobileText`: the full text of the last message the user sent to the shortcode.
* `keywordName`: the keyword used by the user to initiate the request.
* `keywordId`: the internal identifier of the keyword used to initiate the request. If no keyword initiated the request (if the flow was broacast, for example), this will be the idenfitier of the trigger.
* `shortCode`: the shortcode with which the user is interacting

Metadata Update hooks will have values set in the request body for the following variables:

* `msisdn`: as above.
* `subscriberId`: the unique identifier for the subscriber whose metadata was updated.
* `metadataId`: the unique identifier of the metadata field which was updated.
* `oldValue`: an array of field values as they were prior to the update.
* `newValue`: a string containing the new value of the metadata field.

These values may be manipulated and accessed using the following methods:

* `getInputs()`: returns `inputs` as an array
* `setInputs($arr)`: set a bunch of inputs at once using a `key=>value` array, overriding those provided by the SMS user.
* `setInput($name, $value)`: set the value of the listed element of *inputs* to the value you provide
* `retrieveInputs(string $method=*xml|json)`: set the value of inputs to those specified by type and method.
* `cleanMobileText(string $keyword=null, $mobileText=null)`: returns the value of mobileText with keywordName removed from the beginning of the message, if present. This is useful in many cases where a keyword may be used to trigger a script but is not used as an input on its own.

###Dynamic Content

Mobilize's Dynamic Content flow component sends a data payload to a given URL and awaits a response, which contains instructions for Mobilize as to the message which should be sent in reply and whether it should maintain an open session window, directing more messages to the same URL. These are represented in the MobilizeHook object by the `endSession` and `response` components. Dynamic Content responses can be manipulated using the following methods:

* `getMethod()`: Returns the value of `method`
* `setMethod(string)`: Sets `method` to 'post' by default or 'get', optionally
* `getFormat()`: Returns the value of `format`
* `setFormat(string)`: Sets `format` to 'xml' by default or 'json', optionally
* `getEnd()`: Returns the value of `endSession`
* `setEnd(bool)`: Sets `endSession` to true or false
* `getResponse()`: Returns the value of `response`
* `setResponse(string)`: Sets the value of `response`
* `output()`: Returns a properly-formatted Dynamic Content object, specifying endSession and response (response will be truncated to the last full word before reaching the set responseLength).
