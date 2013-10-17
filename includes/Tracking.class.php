<?php

class AC_Tracking extends ActiveCampaign {

	public $url;
	public $api_key;

	function __construct($url, $api_key) {
		$this->url = $url;
		$this->api_key = $api_key;
	}

	function log($params, $post_data) {
		$request_url = "{$this->url}&api_action=tracking_log&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

}

?>