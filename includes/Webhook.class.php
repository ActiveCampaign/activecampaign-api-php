<?php

class AC_Webhook extends ActiveCampaign {

	public $url;
	public $api_key;

	function __construct($url, $api_key) {
		$this->url = $url;
		$this->api_key = $api_key;
	}

	function add($params, $post_data) {
		$request_url = "{$this->url}&api_action=webhook_add&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function delete($params) {
		$request_url = "{$this->url}&api_action=webhook_delete&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function delete_list($params) {
		$request_url = "{$this->url}&api_action=webhook_delete_list&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function edit($params, $post_data) {
		$request_url = "{$this->url}&api_action=webhook_edit&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function list_($params) {
		$request_url = "{$this->url}&api_action=webhook_list&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function view($params) {
		$request_url = "{$this->url}&api_action=webhook_view&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function events($params) {
		$request_url = "{$this->url}&api_action=webhook_events&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

}

?>