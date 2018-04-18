## Varena Fun Data PHP SDK

### Rqquires

- php version >= 7.0.0

- guzzle


### Install

````
composer install varena/sdk

````

composer.json config varena/sdk info


### How to use


````
use Varena\SDK\Request\VarenaRequest;

$client = new VarenaRequest($key,$sercert);

//Get
$reponse = $client->getData($uri,$params);
//Post
$reponse = $client->postData($uri,$params);

var_dump($response);die;
````