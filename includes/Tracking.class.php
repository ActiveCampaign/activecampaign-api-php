<?php

class AC_Tracking extends ActiveCampaign {

	public $url;
	public $api_key;

	function __construct($url, $api_key) {
		$this->url = $url;
		$this->api_key = $api_key;
	}

	function log($params, $post_data) {
		$request_url = "https://trackcmp.net/event";
		$user = $this->api("user/me");
		$post_data["actid"] = $user->trackid;
		$post_data["key"] = $user->eventkey;
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

}

?>