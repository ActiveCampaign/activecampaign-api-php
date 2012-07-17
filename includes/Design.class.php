<?php

class AC_Design extends ActiveCampaign {

	public $url;
	public $api_key;

	function __construct($url, $api_key) {
		$this->url = $url;
		$this->api_key = $api_key;
	}

	function edit($params, $post_data) {
		$request_url = "{$this->url}&api_action=branding_edit&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function view($params, $post_data) {
		$request_url = "{$this->url}&api_action=branding_view&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

}

?>