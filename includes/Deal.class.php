<?php

class AC_Deal extends ActiveCampaign {

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
		$request_url = "{$this->url}&api_action=deal_add&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function edit($params, $post_data) {
		$request_url = "{$this->url}&api_action=deal_edit&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function delete($params, $post_data) {
		$request_url = "{$this->url}&api_action=deal_delete&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function get($params) {
		$request_url = "{$this->url}&api_action=deal_get&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function list_($params) {
		$request_url = "{$this->url}&api_action=deal_list&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function note_add($params, $post_data) {
		$request_url = "{$this->url}&api_action=deal_note_add&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function note_edit($params, $post_data) {
		$request_url = "{$this->url}&api_action=deal_note_edit&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function pipeline_add($params, $post_data) {
		$request_url = "{$this->url}&api_action=deal_pipeline_add&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function pipeline_edit($params, $post_data) {
		$request_url = "{$this->url}&api_action=deal_pipeline_edit&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function pipeline_delete($params, $post_data) {
		$request_url = "{$this->url}&api_action=deal_pipeline_delete&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function pipeline_list($params) {
		$request_url = "{$this->url}&api_action=deal_pipeline_list&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function stage_add($params, $post_data) {
		$request_url = "{$this->url}&api_action=deal_stage_add&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function stage_edit($params, $post_data) {
		$request_url = "{$this->url}&api_action=deal_stage_edit&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function stage_delete($params, $post_data) {
		$request_url = "{$this->url}&api_action=deal_stage_delete&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function stage_list($params) {
		$request_url = "{$this->url}&api_action=deal_stage_list&api_output={$this->output}&{$params}";
		$response = $this->curl($request_url);
		return $response;
	}

	function task_add($params, $post_data) {
		$request_url = "{$this->url}&api_action=deal_task_add&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function task_edit($params, $post_data) {
		$request_url = "{$this->url}&api_action=deal_task_edit&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function tasktype_add($params, $post_data) {
		$request_url = "{$this->url}&api_action=deal_tasktype_add&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function tasktype_edit($params, $post_data) {
		$request_url = "{$this->url}&api_action=deal_tasktype_edit&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

	function tasktype_delete($params, $post_data) {
		$request_url = "{$this->url}&api_action=deal_tasktype_delete&api_output={$this->output}";
		$response = $this->curl($request_url, $post_data);
		return $response;
	}

}

?>