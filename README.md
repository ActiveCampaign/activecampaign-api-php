This is the official PHP wrapper for the ActiveCampaign API. The purpose of these files is to provide a simple interface to the ActiveCampaign API. You are **not** required to use these files (in order to use the ActiveCampaign API), but it's recommended for a few reasons:

1. It's a lot easier to get set up and use (as opposed to coding everything from scratch on your own).
2. It's fully supported by ActiveCampaign, meaning we fix any issues immediately, as well as continually improve the wrapper as the software changes and evolves.
3. It's often the standard approach for demonstrating API requests when using ActiveCampaign support.

Both customers of our hosted platform and On-Site edition can use these files. On-Site customers should clone the source and switch to the "onsite" branch, as that is geared towards the On-Site edition. Many features of the hosted platform are not available in the On-Site edition.

## Installation

You can install **activecampaign-api-php** by downloading or cloning the source.

[Click here to download the source (.zip)](https://github.com/ActiveCampaign/activecampaign-api-php/zipball/master) which includes all dependencies.

`require_once("includes/ActiveCampaign.class.php");`

Fill in your URL and API Key in the `includes/config.php` file, and you are good to go!

## Example Usage

### includes/config.php

<pre>
define("ACTIVECAMPAIGN_URL", "https://ACCOUNT.api-us1.com");
define("ACTIVECAMPAIGN_API_KEY", "njasdf89hy...23ad7");
</pre>

### examples.php

<pre>
require_once("includes/ActiveCampaign.class.php");

$ac = new ActiveCampaign(ACTIVECAMPAIGN_URL, ACTIVECAMPAIGN_API_KEY);

$account = $ac->api("account/view");
</pre>

Or just include everything in the same PHP file:

<pre>
define("ACTIVECAMPAIGN_URL", "https://ACCOUNT.api-us1.com");
define("ACTIVECAMPAIGN_API_KEY", "njasdf89hy...23ad7");
require_once("includes/ActiveCampaign.class.php");
$ac = new ActiveCampaign(ACTIVECAMPAIGN_URL, ACTIVECAMPAIGN_API_KEY);

$account = $ac->api("account/view");
</pre>

See our [examples file](https://github.com/ActiveCampaign/activecampaign-api-php/blob/master/examples.php) for more in-depth samples.

## Full Documentation

[Click here to view our full API documentation.](http://activecampaign.com/api)

## Reporting Issues

We'd love to help if you have questions or problems. Report issues using the [Github Issue Tracker](https://github.com/ActiveCampaign/activecampaign-api-php/issues) or email help@activecampaign.com.