## Installation

You can install **activecampaign-php** by downloading the source.

[Click here to download the source (.zip)](https://github.com/ActiveCampaign/activecampaign-api-php/zipball/master) which includes all dependencies.

`require_once("ActiveCampaign.class.php");`

Fill in your URL and API Key, and you are good to go!

## Example Usage

<pre>
require_once("ActiveCampaign.class.php");

define("ACTIVECAMPAIGN_URL", "YOUR ACTIVECAMPAIGN URL");
define("ACTIVECAMPAIGN_API_KEY", "YOUR ACTIVECAMPAIGN API KEY");

$ac = new ActiveCampaign(ACTIVECAMPAIGN_URL, ACTIVECAMPAIGN_API_KEY);

$account = $ac->api("account/view");
</pre>

## Prerequisites

1. A valid ActiveCampaign account (trial or paid).
2. ActiveCampaign version >= 5.4 (only Onsite users need to worry about this).
3. PHP version >= 5.3.
4. PHP JSON extension.

## Full Documentation

[Click here to view our full API documentation.](http://activecampaign.com/api)

## Reporting Issues

We'd love to help if you have questions or problems. Report issues using the [Github Issue Tracker](https://github.com/ActiveCampaign/activecampaign-api-php/issues) or email support@activecampaign.com.