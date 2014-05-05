#About the Mobilize Library

PHP client library for the Mobilize SMS application from Revolution Messaging, LLC.

This library comes in two parts: the MobilizeHook Class, used to build webservices to receieve requests from the Mobilize platform, and the Mobilize API library, for using the API directly. These two parts do not depend on one another, but are often used in tandem.

##The Revmsg\Mobilize\Hook Class

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

Instantiation function: `new \Revmsg\Mobilize\Hook(format, method, retrieve, responseLength);`
* `format` ('xml'|'json'): the format the instance will use to pull in data from the SMS request and to output dynamic content objects; defaults to 'xml'
* `method` ('get'|'post'): the method by which data is being sent to the object from the SMS request; defaults to 'post'
* `retrieve` (boolean): tells the object whether to pull in data from the SMS request upon instantiation; defaults to true
* `responseLength` (int): sets the maximum length of a dynamic content response; defaults to the US standard of 160

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

##Mobilize API Wrapper

This wrapper allows for the manipulation of objects in the Mobilize platform directly, and requires authentication credentials for the platform in order to function. Contact info@revolutionmessaging.com to inquire about messaging plans and to obtain credentials.

###General use
Every type of object in the Mobilize platform is represented by a separate class, and instances of these classes may all be handled independantly. Objects may be instantiated in any of three ways:
* `new object();`: Create an empty object locally
* `new object(string);`: Pull an object from Mobilize with an ID matching the given string
* `new object(array);`: Create an object locally whose properties match those in the array
####Standard Properties
Many objects share similar properties; though these are not shared by all, where they do appear, all of the following properties have similar definitions. The following table describes these properties and how they are used in each of the standard methods:

|property        |type   |definition                                       |Create     |Retrieve     |Update     |Delete   |
|----------------|-------|-------------------------------------------------|-----------|-------------|-----------|---------|
|id              |24-digit hexadecimal string |the object's ID in the Platform                  |ignored    |required     |ignored    |required |
|status          |text (ACTIVE*, INACTIVE)    |determines whether the object is viewable to users |mandatory, unique  |ignored    |ignored   |ignored     |
|shortCode       |24-digit hexadecimal string |the SMS short code to which the object belongs   |ignored    |ignored    |  |ignored    |
|group           |24-digit hexadecimal string |the permissions group to which the object belongs|ignored    |ignored    |ignored      |ignored    |
|account         |24-digit hexadecimal string |the master account to which the object belongs   |ignored    |ignored    |ignored      |ignored    |
|createdBy       |24-digit hexadecimal string |the user ID of the object's creator              |ignored    |ignored    |ignored      |ignored    |

Properties of objects may be accessed and changed as follows:
* `$object->property = value`: set property of the object to value, provided value is valid and property exists.
* `$object->setVariables(array)`: set all values of the object to match those in the array
* `$object->set(property,value)`: set property of the object to value, provided value is valid and property exists. This method returns the object itself when successful, so it may be chained to set multiple values at once or used with other methods.
* `print $object`: using the object as a string produces a JSON representation of its properties

####Standard Methods
Platform objects have methods corresponding to standard CRUD (create, retrieve, update, delete) operations where applicable. Except where otherwise noted below, these methods work as follows:
*`object->create($version='v1', $session)` creates an object on the Mobilize platform that matches the properties of your local object.
*`object->retrieve($objectId $version='v1', $session)` Updates your local object to match the properties of the existing object with $objectId on the platform.
*`object->update($version='v1', $session)` alters your local object's counterpart on the platform to match its local properties. Note that this method requires the object's id property to match the one on the platform.
*`object->delete($version='v1', $session)` removes the object on the Mobilize platform that matches the id property of your local object. The local object is preserved.

In all CRUD methods, $version defaults to 'v1' but may be changed to utilize a later version of Mobilize API. $session is an optional parameter for use with session authentication; if ommitted, the method attempts to use the value of constant REVMSG_MOBILIZE_KEY as an API key. 

###Authentication
There are two ways to authenticate calls to the Mobilize platform: authenticate the session, or authenticate each request. For an application that will be used for multiple users, session authentication is best; if you are building an application that will use the same credentials every time, using an API key is best.

#####Session Authentication
To create a session, instantiate the Authentication class and call its create() method. You can set your username and password at instantiation or afterward
```
$session = new Authentication();
$session->set('username',username)->set('password',password)->create();
```
or
```
$session = new Authentication(
    array(
        'username' => 'username',
        'password' => 'password'
    )
);
$session->create();
```
You will need to pass the session into subsequent operations.
#####API Key Authentication
You may also authenticate each request to the Mobilize API separately by defining the constant REVMSG_MOBILIZE_KEY as a valid Mobilize API key. This constant is used for any API request that does not include a session.
#####Methods
######Retrieve
`$session->retrieve($version, $session)` provides the user information corresponding to the active session, if present or the active API key, if set.
#####Delete
`$session->delete($version, $session)` logs out the active session.
###Lists
####Properties
#####Standard Properties
`id`
`shortCode`
`status`
`name`
`group`

#####Object-Specific Properties
|property        |type   |definition                                       |Create     |Retrieve     |Update     |Delete   |
|----------------|-------|-------------------------------------------------|-----------|-------------|-----------|---------|
|noOfSubscribers |integer|                     |the number of mobile lines subscribed to the list|ignored    |ignored    |ignored   |ignored |

####Methods
#####Standard Methods
*`create`
*`retrieve`
*`update`
*`delete` removes the list and disassosciates all subscribers from it

###Metadata
####Properties
#####Standard Properties
`id`
`status`
`name`
`group`
#####Object-Specific Properties
|property        |type   |definition                                       |Create     |Retrieve     |Update     |Delete   |
|----------------|-------|-------------------------------------------------|-----------|-------------|-----------|---------|
|validValues     |array  |the list of values the field will allow          |           |ignored      |           | ignored |
|scope |string (GROUP*, ACCOUNT, SYSTEM)|determines the visibility & writability of this field for other permissions groups|ignored |ignored|ignored|ignored|
|eventUrl |string (url)|a webservice URL that should be requested any time a value of this field is changed|ignored|ignored||ignored|
|multiValue |boolean (false)*|when set to true, the field acts as an array on the platform|required |ignored|    |ignored|
|format |regular expression (default: .{1,160})|all data passed to this field must match this regular expression|required |ignored|    |ignored|

####Methods
#####Standard Methods
*`create`
*`retrieve`
*`update`
*`delete` removes the metadata field and all data for that field on all subscribers

###Metadata
####Properties
#####Standard Properties
*`id`
#####Object-Specific Properties
|property        |type          |definition                                                       |Retrieve     |
|----------------|--------------|-----------------------------------------------------------------|-------------|
|blacklist       |array         |array elements are shortcode IDs the subscriber is blacklisted on|ignored      |          
|mobilePhoneNo   |numeric string|the subscriber's phone number in international format            |ignored      |           
|subscriberMetaData|array       |metadata on the subscriber's record                              |ignored      | 
|listDetails     |array         |lists the subscriber is on                                       |ignored      | 

####Methods
#####Standard Methods
*`retrieve`
