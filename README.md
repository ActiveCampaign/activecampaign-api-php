## Installation

You can install **activecampaign-api-php** by downloading the source.

[Click here to download the source (.zip)](https://github.com/ActiveCampaign/activecampaign-api-php/zipball/master) which includes all dependencies.

`require_once("includes/ActiveCampaign.class.php");`

Fill in your URL and API Key in the `config.php` file, and you are good to go!

## Example Usage

### includes/config.php

<pre>
define("ACTIVECAMPAIGN_URL", "YOUR ACTIVECAMPAIGN URL");
define("ACTIVECAMPAIGN_API_KEY", "YOUR ACTIVECAMPAIGN API KEY");
</pre>

### examples.php

<pre>
require_once("includes/ActiveCampaign.class.php");

$ac = new ActiveCampaign(ACTIVECAMPAIGN_URL, ACTIVECAMPAIGN_API_KEY);

$account = $ac->api("account/view");
</pre>

See our [examples file](https://github.com/ActiveCampaign/activecampaign-api-php/blob/master/examples.php) for more in-depth samples.

## Prerequisites

1. A valid ActiveCampaign **hosted** account (trial or paid).

## Full Documentation

[Click here to view our full API documentation.](http://activecampaign.com/api)

## Reporting Issues

We'd love to help if you have questions or problems. Report issues using the [Github Issue Tracker](https://github.com/ActiveCampaign/activecampaign-api-php/issues) or email support@activecampaign.com.