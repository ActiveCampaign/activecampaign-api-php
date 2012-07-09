<?php

if ( !defined("ACTIVECAMPAIGN_URL") || !defined("ACTIVECAMPAIGN_API_KEY") ) {
	include "config.php";
}

require_once("Connector.class.php");

class ActiveCampaign extends Connector {

	public $url;
	public $api_key;

	function __construct($url, $api_key) {
		$this->url = $url;
		$this->api_key = $api_key;
		Connector::__construct($url, $api_key);
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
			$method = $components[1];
			$params = "";
		}
		if ($component == "list") $component = "list_"; // reserved word
		$class = ucwords($component); // IE: "subscriber" becomes "Subscriber"
		// IE: new Subscriber();
		$class = new $class($this->url, $this->api_key);
		// IE: $subscriber->view();
		if ($method == "list") $method = "list_"; // reserved word
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

?>