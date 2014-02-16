<?php

class AC_Account extends ActiveCampaign {

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

	function add($params, $post_data) {
		$request_url = "{$this->url}&api_action=account_add&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function cancel($params) {
		$request_url = "{$this->url}&api_action=account_cancel&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function edit($params, $post_data) {
		$request_url = "{$this->url}&api_action=account_edit&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function list_($params) {
		$request_url = "{$this->url}&api_action=account_list&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function name_check($params) {
		$request_url = "{$this->url}&api_action=account_name_check&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function plans($params) {
		$request_url = "{$this->url}&api_action=account_plans&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function status($params) {
		$request_url = "{$this->url}&api_action=account_status&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function status_set($params) {
		$request_url = "{$this->url}&api_action=account_status_set&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function view() {
		$request_url = "{$this->url}&api_action=account_view&api_output={$this->output}";
		$response = $this->curl($request_url);
		return $response;
	}

}

?>