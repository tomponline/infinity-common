HTTP Request
=====================

The HTTP Request object allows you to access information about HTTP requests.

Example usage:

```php
use Infinity\Common\Http\Request;

$request = new Request();

var_dump($request->getBodyParameters());
var_dump($request->getRawBody());

```

Land Helper
=====================

The Land Helper provides a process for detecting whether a request is
a land or a page view (based on referrer domain) and provides the ability
to detect and store persisted land URL parameters.

Example usage:

```php
use Infinity\Common\Http\LandHelper;

$landHelper = new LandHelper();

$persistParams = array(
    'myTag' => 'session',   //Stores this in a cookie for the session.
    'tag2'  => 'P2D',       //Stores this in a cookie for 2 days
);

$landHelper->setPersistedLandParamRules( $persistPams );
$landHelper->run();

var_dump($landHelper->isLand()); //Returns TRUE or FALSE

```

HTTP Client
=====================

The HTTP Client provides an interface for setting up and processing HTTP requests

Example usage:

```php
use Infinity\Common\Http\Client;

$client = new Client();

//Set the URI
$client->setRequestUri( 'localhost' );

//Set request data
$client->setRequestData( array() );
$client->setRequestMethod( 'POST' );

//Set request data and method
$client->setRequestPostData( array() ); //POST
$client->setRequestPutData( array() ); //PUT
$client->setRequestDeleteData( array() ); //DELETE

//Sets request credentials
$client->setRequestCredentials( 'user', 'passwd' );

//Set request headers
$client->setRequestheader( 'User-Agent', 'user-agent string' );

//Advanced
$client->setChecksMode( *bool* );
$client->setFollowRedirects( *bool* );
$client->setMemoryLimit( *int* ); //Memory limit in bytes
$client->setProxy( *string* ); //Proxy address
$client->setProxyPort( *string* ); //Proxy port

//Send request
$client->sendRequest();

//Response
var_dump($client->getResponseHeader( 'content-type' ));
var_dump($client->getResponseCode());
var_dump(stream_get_contents( $client->getResponseHandle() ));
var_dump($client->getResponseSize());
var_dump($client->getResponseResult());

//Errors
var_dump($client->getError());

```
