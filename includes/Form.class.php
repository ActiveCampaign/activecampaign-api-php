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
		$id_expression = (isset($params_array[0])) ? $params_array[0] : "id=0"; // id=24
		$css_expression = (isset($params_array[1])) ? $params_array[1] : "css=1"; // css=1
		$ajax_expression = (isset($params_array[2])) ? $params_array[2] : "ajax=0"; // ajax=0
		$action_expression = (isset($params_array[3])) ? $params_array[3] : ""; // action=http://someurl.com
		$html = $this->html($id_expression);
		if ($html) {
			// remove the action attribute
			$html = preg_replace("/action=['\"][^'\"]+['\"]/", "", $html);
			// replace the Submit button to be an actual submit type.
			$html = preg_replace("/input type='button'/", "input type='submit'", $html);
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