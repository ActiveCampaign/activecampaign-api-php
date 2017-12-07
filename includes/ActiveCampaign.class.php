<?php

if ( !defined("ACTIVECAMPAIGN_URL") || (!defined("ACTIVECAMPAIGN_API_KEY") && !defined("ACTIVECAMPAIGN_API_USER") && !defined("ACTIVECAMPAIGN_API_PASS")) ) {
	require_once(dirname(__FILE__) . "/config.php");
}

require_once("Connector.class.php");

/**
 * Class ActiveCampaign
 */
class ActiveCampaign extends AC_Connector {

	/**
	 * @var
	 */
	public $url_base;

	/**
	 * @var
	 */
	public $url;

	/**
	 * @var
	 */
	public $api_key;

	/**
	 * @var
	 */
	public $track_email;

	/**
	 * @var
	 */
	public $track_actid;

	/**
	 * @var
	 */
	public $track_key;

	/**
	 * @var int
	 */
	public $version = 1;

	/**
	 * @var bool
	 */
	public $debug = false;

	/**
	 * @var string
	 */
	public $curl_response_error = "";

	/**
	 * ActiveCampaign constructor.
	 *
	 * @param        $url
	 * @param        $api_key
	 * @param string $api_user
	 * @param string $api_pass
	 */
	function __construct($url, $api_key, $api_user = "", $api_pass = "") {
		$this->url_base = $this->url = $url;
		$this->api_key = $api_key;
		parent::__construct($url, $api_key, $api_user, $api_pass);
	}

	/**
	 * Set the version on the url
	 *
	 * @param $version
	 */
	function version($version) {
		$this->version = (int)$version;
		if ($version == 2) {
			$this->url_base = $this->url_base . "/2";
		}
	}

	/**
	 * Make api calls
	 *
	 * @param       $path
	 * @param array $post_data
	 *
	 * @return mixed
	 */
	function api($path, $post_data = array()) {
		// IE: "contact/view"
		$components = explode("/", $path);
		$component = $components[0];

		if (count($components) > 2) {
			// IE: "contact/tag/add?whatever"
			// shift off the first item (the component, IE: "contact").
			array_shift($components);
			// IE: convert to "tag_add?whatever"
			$method_str = implode("_", $components);
			$components = array($component, $method_str);
		}

		if (preg_match("/\?/", $components[1])) {
			// query params appended to method
			// IE: contact/edit?overwrite=0
			$method_arr = explode("?", $components[1]);
			$method = $method_arr[0];
			$params = $method_arr[1];
		}
		else {
			// just a method provided
			// IE: "contact/view
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
			$component = "contact";
			$method = "sync";
		}
		elseif ($component == "singlesignon") {
			$component = "auth";
		}

		$class = ucwords($component); // IE: "contact" becomes "Contact"
		$class = "AC_" . $class;
		// IE: new Contact();

		$add_tracking = false;
		if ($class == "AC_Tracking") $add_tracking = true;
		if ($class == "AC_Tags") {
			$class = "AC_Tag";
		}

		$class = new $class($this->version, $this->url_base, $this->url, $this->api_key);

		$class->set_curl_timeout($this->get_curl_timeout());

		if ($add_tracking) {
			$class->track_email = $this->track_email;
			$class->track_actid = $this->track_actid;
			$class->track_key = $this->track_key;
		}

		if ($method == "list") {
			// reserved word
			$method = "list_";
		}

		$class->debug = $this->debug;

		$response = $class->$method($params, $post_data);
		return $response;
	}
}

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
