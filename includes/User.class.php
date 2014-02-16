<?php

class AC_User extends ActiveCampaign {

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
		$request_url = "{$this->url}&api_action=user_add&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function delete_list($params) {
		$request_url = "{$this->url}&api_action=user_delete_list&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function delete($params) {
		$request_url = "{$this->url}&api_action=user_delete&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function edit($params, $post_data) {
		$request_url = "{$this->url}&api_action=user_edit&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function list_($params) {
		$request_url = "{$this->url}&api_action=user_list&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function me() {
		$request_url = "{$this->url}&api_action=user_me&api_output={$this->output}";
		$response = $this->curl($request_url);
		return $response;
	}

	function view($params) {
		// can be a user ID, email, or username
		if (preg_match("/^email=/", $params)) {
			$action = "user_view_email";
		}
		elseif (preg_match("/^username=/", $params)) {
			$action = "user_view_username";
		}
		elseif (preg_match("/^id=/", $params)) {
			$action = "user_view";
		}
		$request_url = "{$this->url}&api_action={$action}&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

}

?>