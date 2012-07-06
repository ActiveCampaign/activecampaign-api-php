<?php

class Auth extends ActiveCampaign {

	public $url;
	public $api_key;

	function __construct($url, $api_key) {
		$this->url = $url;
		$this->api_key = $api_key;
	}

	function singlesignon($params) {
		$request_url = "{$this->url}&api_action=singlesignon&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function singlesignon_sameserver($params) {
		$request_url = "{$this->url}&api_action=singlesignon_sameserver&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

}

?>