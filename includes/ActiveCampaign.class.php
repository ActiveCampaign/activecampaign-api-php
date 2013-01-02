<?php

if ( !defined("ACTIVECAMPAIGN_URL") || (!defined("ACTIVECAMPAIGN_API_KEY") && !defined("ACTIVECAMPAIGN_API_USER") && !defined("ACTIVECAMPAIGN_API_PASS")) ) {
	include "config.php";
}

require_once("Connector.class.php");

class ActiveCampaign extends AC_Connector {

	public $url;
	public $api_key;

	function __construct($url, $api_key, $api_user = "", $api_pass = "") {
		$this->url = $url;
		$this->api_key = $api_key;
		AC_Connector::__construct($url, $api_key, $api_user, $api_pass);
	}

	function api($path, $post_data = array()) {
		// IE: "subscriber/view"
		$components = explode("/", $path);
		$component = $components[0];

		if (preg_match("/\?/", $components[1])) {
			// query params appended to method
			// IE: subscriber/edit?overwrite=0
			$method_arr = explode("?", $components[1]);
			$method = $method_arr[0];
			$params = $method_arr[1];
		}
		else {
			// just a method provided
			// IE: "subscriber/view
			if ( isset($components[1]) ) {
				$method = $components[1];
				$params = "";
			}
			else {
				return "Invalid method.";
			}
		}

		// adjustments
		if ($component == "list") {
			// reserved word
			$component = "list_";
		}
		elseif ($component == "branding") {
			$component = "design";
		}
		elseif ($component == "sync") {
			$component = "subscriber";
			$method = "sync";
		}
		elseif ($component == "singlesignon") {
			$component = "auth";
		}

		$class = ucwords($component); // IE: "subscriber" becomes "Subscriber"
		$class = "AC_" . $class;
		// IE: new Subscriber();

		$class = new $class($this->url, $this->api_key);
		// IE: $subscriber->view();

		if ($method == "list") {
			// reserved word
			$method = "list_";
		}

		$response = $class->$method($params, $post_data);
		return $response;
	}

}

require_once("Account.class.php");
require_once("Auth.class.php");
require_once("Campaign.class.php");
require_once("Design.class.php");
require_once("Form.class.php");
require_once("Group.class.php");
require_once("List.class.php");
require_once("Message.class.php");
require_once("Subscriber.class.php");
require_once("User.class.php");
require_once("Webhook.class.php");

?>