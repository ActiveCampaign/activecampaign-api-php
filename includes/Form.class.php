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

	function embed($params) {

		$params_array = explode("&", $params);
		$params_ = array();
		foreach ($params_array as $expression) {
			// IE: css=1
			list($var, $val) = explode("=", $expression);
			$params_[$var] = $val;
		}

		$id = (isset($params_["id"])) ? (int)$params_["id"] : 0;
		$css = (isset($params_["css"])) ? (int)$params_["css"] : 1;
		$ajax = (isset($params_["ajax"])) ? (int)$params_["ajax"] : 0;
		$action = (isset($params_["action"])) ? $params_["action"] : "";

		$html = $this->html("id={$id}");

		if (is_object($html) && !(int)$html->success) {
			return $html->error;
		}

		if ($html) {

			if (!$css) {
				// remove all CSS
				$html = preg_replace("/<style[^>]*>(.*)<\/style>/s", "", $html);
			}

			if ($action) {
				// replace the action attribute with the one provided
				$html = preg_replace("/action=['\"][^'\"]+['\"]/", "action='{$action}'", $html);
			}
			
			if (!$ajax) {
				// replace the Submit button to be an actual submit type
				$html = preg_replace("/input type='button'/", "input type='submit'", $html);
			}
			else {
				// if using Ajax, remove the action attribute
				$html = preg_replace("/action=['\"][^'\"]+['\"]/", "", $html);
				
				// add jQuery stuff
				$js = "<script type='text/javascript'>
				
$(document).ready(function () {
	
	$.ajax({
		url: '',
		dataType: 'json',
		error: function(jqXHR, textStatus, errorThrown) {
		},
		success: function(data) {
		}
	});
	
});			

</script>";
				
				$html = $html . $js;
			}

		}

		return $html;
	}

	function process() {
		if ($_SERVER["REQUEST_METHOD"] != "POST") return;
		if (isset($_POST["fullname"])) {
			$fullname = explode(" ", $_POST["fullname"]);
			$firstname = array_shift($fullname);
			$lastname = implode(" ", $fullname);
		}
		else {
			$firstname = trim($_POST["firstname"]);
			$lastname = trim($_POST["lastname"]);

			if ($firstname == "") $firstname = trim($_POST["first_name"]);
			if ($lastname == "") $lastname = trim($_POST["last_name"]);
		}
	}

}

?>