<?php

class AC_Graph extends ActiveCampaign {

	public $url;
	public $api_key;

	function __construct($url, $api_key) {
		$this->url = $url;
		$this->api_key = $api_key;
	}

	function subscriber_rate() {
		$request_url = "{$this->url}&api_action=graph_subscriber_rate&api_output={$this->output}";
		$response = $this->curl($request_url);
		return $response;
	}

}

?>