<?php

class AC_Form extends ActiveCampaign {

	public $url;
	public $api_key;

	function __construct($url, $api_key) {
		$this->url = $url;
		$this->api_key = $api_key;
	}

	function getforms($params) {
		$request_url = "{$this->url}&api_action=form_getforms&api_output={$this->output}";
		$response = $this->curl($request_url);
		return $response;
	}

	function html($params) {
		$request_url = "{$this->url}&api_action=form_html&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

}

?>