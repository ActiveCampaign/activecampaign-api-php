# ActiveCampaign PHP API Wrapper

This is the official PHP wrapper for the ActiveCampaign API. The purpose of these files is to provide a simple interface to the ActiveCampaign API. You are **not** required to use these files (in order to use the ActiveCampaign API), but it's recommended for a few reasons:

1. It's a lot easier to get set up and use (as opposed to coding everything from scratch on your own).
2. It's fully supported by ActiveCampaign, meaning we fix any issues immediately, as well as continually improve the wrapper as the software changes and evolves.
3. It's often the standard approach for demonstrating API requests when using ActiveCampaign support.

Both customers of our hosted platform and On-Site edition can use these files. On-Site customers should clone the source and switch to the <a href="https://github.com/ActiveCampaign/activecampaign-api-php/tree/onsite">"onsite" branch</a>, as that is geared towards the On-Site edition. Many features of the hosted platform are not available in the On-Site edition.

## Installation

You can install **activecampaign-api-php** by [downloading (.zip)](https://github.com/ActiveCampaign/activecampaign-api-php/zipball/master) or cloning the source:

`git clone git@github.com:ActiveCampaign/activecampaign-api-php.git`

### Composer

If you are using Composer, create your `composer.json` file ([example here](examples-composer/composer.json)).

Then load the `composer.phar` file in that directory:

`curl -sS https://getcomposer.org/installer | php`

Next, run install to load the ActiveCampaign library:

`php composer.phar install`

You should then see the `activecampaign` folder inside `vendor`.

[Read more about using Composer](https://getcomposer.org/doc/).

## Example Usage

### Composer

In your script just include the `autoload.php` file to load all classes:

```php
require "vendor/autoload.php";
```

Next, create a class instance of `ActiveCampaign`:

```php
use ActiveCampaign\Api\V1\ActiveCampaign;

$ac = new ActiveCampaign("API_URL", "API_KEY");
```

That's it!

### Full Example

```php
use ActiveCampaign\Api\V1\ActiveCampaign;

$ac = new ActiveCampaign("API_URL", "API_KEY");

// Adjust the default cURL timeout (defaults to 30 seconds)
$ac->set_curl_timeout(10);

$account = $ac->api("account/view");
```

## Full Documentation

[Click here to view our full API documentation.](https://www.activecampaign.com/api/overview.php)

## Reporting Issues

We'd love to help if you have questions or problems. Report issues using the [Github Issue Tracker](https://github.com/ActiveCampaign/activecampaign-api-php/issues) or email help@activecampaign.com.
