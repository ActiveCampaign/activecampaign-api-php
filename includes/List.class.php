<?php

class AC_List_ extends ActiveCampaign {

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
		$request_url = "{$this->url}&api_action=list_add&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function delete_list($params) {
		$request_url = "{$this->url}&api_action=list_delete_list&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function delete($params) {
		$request_url = "{$this->url}&api_action=list_delete&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function edit($params, $post_data) {
		$request_url = "{$this->url}&api_action=list_edit&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function field_add($params, $post_data) {
		$request_url = "{$this->url}&api_action=list_field_add&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function field_delete($params) {
		$request_url = "{$this->url}&api_action=list_field_delete&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function field_edit($params, $post_data) {
		$request_url = "{$this->url}&api_action=list_field_edit&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function field_view($params) {
		$request_url = "{$this->url}&api_action=list_field_view&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function list_($params, $post_data) {
		if ($post_data) {
			if (isset($post_data["ids"]) && is_array($post_data["ids"])) {
				// make them comma-separated.
				$post_data["ids"] = implode(",", $post_data["ids"]);
			}
		}
		$request_url = "{$this->url}&api_action=list_list&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function paginator($params) {
		$request_url = "{$this->url}&api_action=list_paginator&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function view($params) {
		$request_url = "{$this->url}&api_action=list_view&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

}

?>