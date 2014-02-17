<?php

class AC_Tracking extends ActiveCampaign {

	public $version;
	public $url_base;
	public $url;
	public $api_key;

	function __construct($version, $url_base, $url, $api_key) {
		$this->version = $version;
		$this->url_base = $url_base;
		$this->url = $url;
		$this->api_key = $api_key;
	}

	/*
	 * Returns existing whitelisted domains.
	 */
	function site_list($params) {
		if ($this->version == 1) {
			// not supported currently.
			//$request_url = "{$this->url}&api_action=contact_delete_list&api_output={$this->output}&{$params}";
		} elseif ($this->version == 2) {
			$request_url = "{$this->url_base}/track/site";
		}
		$response = $this->curl($request_url, array(), "GET", "tracking_site_list");
		return $response;
	}

	/*
	 * Returns existing tracked events.
	 */
	function event_list($params) {
		if ($this->version == 1) {
			// not supported currently.
			//$request_url = "{$this->url}&api_action=contact_delete_list&api_output={$this->output}&{$params}";
		} elseif ($this->version == 2) {
			$request_url = "{$this->url_base}/track/event";
		}
		$response = $this->curl($request_url, array(), "GET", "tracking_event_list");
		return $response;
	}

	/*
	 * Adds a domain to the site tracking whitelist.
	 */
	function whitelist($params, $post_data) {
		// version 2 only.
		$request_url = "{$this->url_base}/track/site";
		$response = $this->curl($request_url, $post_data, "PUT", "tracking_whitelist");
		return $response;
	}

	/*
	 * Adds a new event.
	 */
	function log($params, $post_data) {
		$request_url = "https://trackcmp.net/event";
		if ($this->track_email) $post_data["visit"] = json_encode(array("email" => $this->track_email));
		$post_data["actid"] = $this->track_actid;
		$post_data["key"] = $this->track_key;
		$response = $this->curl($request_url, $post_data, "POST", "tracking_log");
		return $response;
	}

}

?>