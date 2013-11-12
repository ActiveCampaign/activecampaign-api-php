<?php

class AC_Tracking extends ActiveCampaign {

	public $url;
	public $api_key;

	function __construct($url, $api_key) {
		$this->url = $url;
		$this->api_key = $api_key;
	}

	function log($params, $post_data) {
		$request_url = "https://trackcmp.net/";
		if ($post_data) {
			$request_url .= "event";
			if ($this->track_email) $post_data["visit"] = json_encode(array("email" => $this->track_email));
			$post_data["actid"] = $this->track_actid;
			$post_data["key"] = $this->track_key;
			$response = $this->curl($request_url, $post_data);
		} else {
			$request_url .= "visit?actid=" . $this->track_actid;
			$request_url .= "&e=" . urlencode($this->track_email);
			$request_url .= "&r=" . urlencode($this->track_referer);
			$request_url .= "&u=" . urlencode($this->track_url);
			$response = $this->curl($request_url);
		}
		return $response;
	}

}

?>