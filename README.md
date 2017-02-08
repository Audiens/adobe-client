# Audiens/adobe-client
[![Build Status](https://travis-ci.org/Audiens/adobe-client.svg?branch=master)](https://travis-ci.org/Audiens/adobe-client)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Audiens/adobe-client/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Audiens/adobe-client/?branch=master)
[![Code Climate](https://codeclimate.com/github/Audiens/adobe-client/badges/gpa.svg)](https://codeclimate.com/github/Audiens/adobe-client)

An OOP implementation af the Adobe API.
  
## Installation
To use this package, use composer:

 * from CLI: `composer require Audiens/adobe-client`
 * or, directly in your `composer.json`:

``` 
{
    "require": {
        "Audiens/adobe-client": "dev-master"
    }
}
```
  
## Features
  At this stage, adobe client implements these three method:
  
  * FindAll: method that retrieves all your traits from adobe
  * findOneById: Retrieve a specified trait by SID param
  * getTrendByTrait: Retrieve the trends report for a specific trait and for a date range
  
  
## Usage


```php

require 'vendor/autoload.php';

$client_id = '{your_client_id'}'
$secret_key = '{?your_secret_key'}'
$username = '{yourUsername}';
$password = '{yourPassword}';

$cache = $cacheToken ? new FilesystemCache('build') : null;
$client = new Client();
$authStrategy = new AdnxStrategy(new Client(), $cache);

$authClient = new Auth($client_id, $secret_key, $username, $password, $client, $authStrategy);

$traitRepository = new TraitRepository($authClient);

$myTraits = $traitRepository->findAll();

```

# Test
Functional and unit tests are located under the "Test" folder. Be aware that the functional test require that you adobe sandbox enviroment 
contains at least one trait.