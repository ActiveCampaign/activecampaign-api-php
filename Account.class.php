<?php

class Account extends ActiveCampaign {

	public $url;
	public $api_key;

	function __construct($url, $api_key) {
		$this->url = $url;
		$this->api_key = $api_key;
	}

	function view() {
		$request_url = "{$this->url}&api_action=account_view&api_output={$this->output}";
		$response = $this->curl($request_url);
		return $response;
	}

}

?>