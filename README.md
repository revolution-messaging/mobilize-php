#About the Mobilize Library

PHP client library for the Mobilize SMS application from Revolution Messaging, LLC.

This library comes in two parts: the MobilizeHook Class, used to build webservices to receieve requests from the Mobilize platform, and the Mobilize API library, for using the API directly. These two parts do not depend on one another, but are often used in tandem.

##The MobilizeHook Class

This class allows for easy access to the Mobilize webhooks which appear in:

* Dynamic Content message flow components
* Post-to-URL message flow components
* Metadata update hooks

###Properties

* `endSession (boolean, defaults to true)`: when outputting a dynamic content object, this value tells Mobilize whether to move on to the next messaging component (true) or to await further input from the user (false).
* `response (string)`: the message that will be sent back to the user when placed in a dynamic content object. Note that response may be any length, but the message will be truncated at the last whitespace before the 160th character when the dynamic content object is outputted.
* `format (xml|json)`: this tells the object what format to expect in the payload and how to output dynamic content objects.
* `method (get|post)`: this tells the object where to look for inputs, either in the URL string or in the request body.
* `inputs (array)`: an array of all values provided by Mobilize, plus the 'strippedText' value (see stripText method for details)

###Instantiation

Upon creating the object, `format` will default to 'xml' and `method` to 'post', unless specified otherwise. The object will also attempt to get all data passed to it according to the values of `format` and `method` using `retrieveInputs()`.

###Using Mobilize Data

Once the class is instantiated, the MobilizeHook object should contain all data delivered by Mobilize in the `inputs` property. Mobilize will always send a request body with the full range of inputs, though not all will be used with all hooks, and by setting `method` to 'get' it is possible to have the MobilizeHook object only look for message data in the URL string.

Dynamic Content and Post-to-URL hooks will have values set in the request body for the following variables:

* `msisdn`: the user's mobile phone number. Typically this will be in 12-digit format (the ten-digit number preceded by 01).
* `mobileText`: the full text of the last message the user sent to the shortcode.
* `keywordName`: the keyword used by the user to initiate the request.
* `keywordId`: the internal identifier of the keyword used to initiate the request. If no keyword initiated the request (if the flow was broacast, for example), this will be the idenfitier of the trigger.
* `shortCode`: the shortcode with which the user is interacting
* `strippedText`: this variable is not sent by Mobilize at all, but will be populated when the object is created. It is equivalent to mobileText with keywordName removed from the beginning of the string.

Metadata Update hooks will have values set in the request body for the following variables:

* `msisdn`: as above.
* `subscriberId`: the unique identifier for the subscriber whose metadata was updated.
* `metadataId`: the unique identifier of the metadata field which was updated.
* `oldValue`: an array of field values as they were prior to the update.
* `newValue`: a string containing the new value of the metadata field.

These values may be manipulated using the following methods:

* `getInputs()`: returns `inputs` as an array
* `setInputs(array)`: set a bunch of inputs at once using a `key=>value` array
* `setInput(name, value)`: set the value of the listed element of *inputs* to the value you provide
* `retrieveInputs(method)`: set the value of inputs to those specified by type and method. This will also call `stripText()`.
* `stripText()`: populate the strippedText element of inputs by removing keywordName from mobileText.

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
* `output()`: Returns a properly-formatted Dynamic Content object, specifying endSession and response (response will be truncated to the last full word before reaching 160 characters).

##Mobilize API Wrapper

This wrapper allows for the manipulation of objects in the Mobilize platform directly, and requires authentication credentials for the platform in order to function. Contact info@revolutionmessaging.com to inquire about messaging plans.

###General use
Every type of object in the Mobilize platform is represented by a separate class, and instances of these classes may all be handled independantly. Objects may be instantiated in any of three ways:
* `new object();`: Create an empty object locally
* `new object(string);`: Pull an object from Mobilize with an ID matching the given string
* `new object(array);`: Create an object locally whose properties match those in the array
Properties of objects may be changed as follows:
* `$object->property = value`: set property of the object to value, provided value is valid and property exists.
* `$object->setVariables(array)`: set all values of the object to match those in the array
* `$object->set(property,value)`: set property of the object to value, provided value is valid and property exists. This method returns the object itself when successful, so it may be chained to set multiple values at once or used with other methods.
Most objects have methods corresponding to CRUD operations, where supported (more to come):

###Authentication
The first step in using the API is creating an authenticated session. 
```
use Revmsg\Mobilize
$session = new Object\authentication();
$session->set('username',username)->set('password',password)->create();
```
This session will be passed to methods defined in other classes.

###Lists
###Metadata


