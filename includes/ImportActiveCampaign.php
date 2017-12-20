<?php

if (!defined("ACTIVECAMPAIGN_URL") || (!defined("ACTIVECAMPAIGN_API_KEY") && !defined("ACTIVECAMPAIGN_API_USER") && !defined("ACTIVECAMPAIGN_API_PASS"))) {
    require_once(dirname(__FILE__) . "/config.php");
}

require_once(dirname(__FILE__) . "/exceptions/RequestException.php");
require_once(dirname(__FILE__) . "/exceptions/TimeoutException.php");
require_once(dirname(__FILE__) . "/exceptions/ClientException.php");
require_once(dirname(__FILE__) . "/exceptions/ServerException.php");
require_once("Connector.class.php");
require_once("ActiveCampaign.class.php");
require_once("Account.class.php");
require_once("Auth.class.php");
require_once("Automation.class.php");
require_once("Campaign.class.php");
require_once("Contact.class.php");
require_once("Deal.class.php");
require_once("Design.class.php");
require_once("Form.class.php");
require_once("Group.class.php");
require_once("List.class.php");
require_once("Message.class.php");
require_once("Organization.class.php");
require_once("Segment.class.php");
require_once("Settings.class.php");
require_once("Subscriber.class.php");
require_once("Tag.class.php");
require_once("Tracking.class.php");
require_once("User.class.php");
require_once("Webhook.class.php");
