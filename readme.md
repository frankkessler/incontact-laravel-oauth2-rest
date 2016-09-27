[![Travis CI Build Status](https://api.travis-ci.org/frankkessler/incontact-laravel-oauth2-rest.svg)](https://travis-ci.org/frankkessler/incontact-laravel-oauth2-rest/)
[![Coverage Status](https://coveralls.io/repos/github/frankkessler/incontact-laravel-oauth2-rest/badge.svg?branch=master)](https://coveralls.io/github/frankkessler/incontact-laravel-oauth2-rest?branch=master)
[![StyleCI](https://styleci.io/repos/45960401/shield)](https://styleci.io/repos/45960401)
[![Latest Stable Version](https://img.shields.io/packagist/v/frankkessler/incontact-laravel-oauth2-rest.svg)](https://packagist.org/packages/frankkessler/incontact-laravel-oauth2-rest)

# INSTALLATION

To install this package, add the following to your composer.json file

```json
frankkessler/incontact-laravel-oauth2-rest: "0.2.*"
```

## LARAVEL 5 SPECIFIC INSTALLATION TASKS
Add the following to your config/app.php file in the providers array

```php
Frankkessler\Incontact\Providers\IncontactLaravelServiceProvider::class,
```

Add the following to your config/app.php file in the aliases array

```php
'Incontact'    => Frankkessler\Incontact\Facades\Incontact::class,
```

Run the following command to pull the project config file and database migration into your project

```bash
php artisan vendor:publish
```

Run the migration

```bash
php artisan migrate
```

##OPTIONAL INSTALLATION

Logging is enabled by default if using Laravel.  If not, add the following to the $config parameter when initializing the Incontact class.  (This class must implement the Psr\Log\LoggerInterface interface.)

```php
'incontact.logger' => $class_or_class_name
```

#TOKEN SETUP

Currently, this package only supports the username/password flow for oauth2.

To get started, you'll have to setup an Application in Incontact.
1. Navigate to Manage -> API Applications
2. Click "Create API Application"
3. Select "Register Internal Application"
4. Fill out the form keeping in mind that your OAUTH_CONSUMER_TOKEN will be [APPLICATION_NAME]@[VENDOR_NAME]
5. Save and find your business unit if which will be your OAUTH_CONSUMER_SECRET.

Now that you have your Consumer Token and Consumer Secret, add them to your .env file:

```php
INCONTACT_OAUTH_DOMAIN=api.incontact.com
INCONTACT_OAUTH_CONSUMER_TOKEN=[APPLICATION_NAME]@[VENDOR_NAME]
INCONTACT_OAUTH_CONSUMER_SECRET=BUSINESS_UNIT_NUMBER
INCONTACT_OAUTH_SCOPES=RealTimeApi AdminApi ReportingApi
INCONTACT_OAUTH_USERNAME=YOUR_INCONTACT_USERNAME
INCONTACT_OAUTH_PASSWORD=YOUR_INCONTACT_PASSWORD
```

# EXAMPLES

## AdminApi

### Get Agents

```php
$incontact = new \Frankkessler\Incontact\Incontact();

$result = $incontact->AdminApi()->agents();

foreach($result['agents'] as $record) {
    $agentId =  $record['AgentId'];
}
```

## ReportingApi

### Get Call by Contact Id

```php
$incontact = new \Frankkessler\Incontact\Incontact();

$result = $incontact->ReportingApi()->contact('9999999999');
                                      
foreach($result as $record) {
    $contactId = $record['contactId'];
}
```