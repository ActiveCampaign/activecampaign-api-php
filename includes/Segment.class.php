<?php

class AC_Segment extends ActiveCampaign {

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

	function list_($params) {
		if ($this->version == 1) {
			$request_url = "{$this->url}&api_action=segment_list&api_output={$this->output}&{$params}";
			$response = $this->curl($request_url);
		} elseif ($this->version == 2) {
			$request_url = "{$this->url_base}/segment/list";
			$response = $this->curl($request_url, $params, "GET", "segment_list");
		}
		return $response;
	}

}

?>