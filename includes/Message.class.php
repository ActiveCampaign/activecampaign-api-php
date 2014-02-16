<?php

class AC_Message extends ActiveCampaign {

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
		$request_url = "{$this->url}&api_action=message_add&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function delete_list($params) {
		$request_url = "{$this->url}&api_action=message_delete_list&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function delete($params) {
		$request_url = "{$this->url}&api_action=message_delete&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function edit($params, $post_data) {
		$request_url = "{$this->url}&api_action=message_edit&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function list_($params) {
		$request_url = "{$this->url}&api_action=message_list&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function template_add($params, $post_data) {
		$request_url = "{$this->url}&api_action=message_template_add&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function template_delete_list($params) {
		$request_url = "{$this->url}&api_action=message_template_delete_list&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function template_delete($params) {
		$request_url = "{$this->url}&api_action=message_template_delete&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function template_edit($params, $post_data) {
		$request_url = "{$this->url}&api_action=message_template_edit&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function template_export($params) {
		$request_url = "{$this->url}&api_action=message_template_export&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function template_import($params, $post_data) {
		$request_url = "{$this->url}&api_action=message_template_import&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function template_list($params) {
		$request_url = "{$this->url}&api_action=message_template_list&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function template_view($params) {
		$request_url = "{$this->url}&api_action=message_template_view&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function view($params) {
		$request_url = "{$this->url}&api_action=message_view&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

}

?>