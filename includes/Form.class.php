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
		// to set the current page as the action, pass "action=", or "action=[THIS URL]"
		$action = (isset($params_["action"])) ? ($params_["action"] ? $params_["action"] : "this") : "";

		$html = $this->html("id={$id}");

		if (is_object($html) && !(int)$html->success) {
			return $html->error;
		}

		if ($html) {

			if ($action) {
				if ($action != "this") {
					// replace the action attribute with the one provided
					$action_val = urldecode($action);
					$html = preg_replace("/action=['\"][^'\"]+['\"]/", "action='{$action_val}'", $html);
				}
				else {
					$action_val = "";
				}
			}
			else {
				// find the action attribute value (URL)
				// should be the proc.php URL (at this point in the script)
				$action_val = preg_match("/action=['\"][^'\"]+['\"]/", $html, $m);
				$action_val = $m[0];
				$action_val = substr($action_val, 8, strlen($action_val) - 9);
			}

			if (!$css) {
				// remove all CSS
				$html = preg_replace("/<style[^>]*>(.*)<\/style>/s", "", $html);
			}

			if (!$ajax) {
				// replace the Submit button to be an actual submit type
				$html = preg_replace("/input type='button'/", "input type='submit'", $html);

				// if action = "this", remove the action attribute completely
				if (!$action_val) {
					$html = preg_replace("/action=['\"][^'\"]+['\"]/", "", $html);
				}
			}
			else {

				// if using Ajax, remove the <form> action attribute completely
				$html = preg_replace("/action=['\"][^'\"]+['\"]/", "", $html);

				$action_val = urldecode($action_val);

				// add jQuery stuff
				$extra = "<script type='text/javascript'>

$(document).ready(function() {

	$('input[type*=\"button\"]').click(function() {

		var form_data = {};
		$('form').each(function() {
			form_data = $(this).serialize();
		});

		var geturl;
		geturl = $.ajax({
			url: '{$action_val}',
			type: 'POST',
			dataType: 'json',
			data: form_data,
			error: function(jqXHR, textStatus, errorThrown) {
				alert('Error: ' + textStatus);
			},
			success: function(data) {
				$('#form_result_message').html(data.message);
			}
		});

	});

});

</script>";

				$html = $html . $extra;
			}

		}

		return $html;
	}

	function process() {
		$r = array();
		if ($_SERVER["REQUEST_METHOD"] != "POST") return $r;
//dbg($_POST);

		$formid = $_POST["f"];
		$email = $_POST["email"];

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

		if ( !isset($_POST["field"]) ) {
			$xf = array();
			foreach ($_POST as $k => $v) {
				if (substr($k, 0, 6) == "field_") {
					$tmparr = explode("_", substr($k, 6));
					if ( count($tmparr) == 2 ) {
						$xf[(int)$tmparr[0] . "," . (int)$tmparr[1]] = $v;
					}
				}
			}
			if ($xf) $_POST["field"] = $xf;
		}

		$subscriber = array(
			"form" => $formid,
			"email" => $email,
			"first_name" => $firstname,
			"last_name" => $lastname,
			"field" => $xf,
		);

		// add lists
		foreach ($_POST["nlbox"] as $listid) {
			$subscriber["p[{$listid}]"] = $listid;
			$subscriber["status[{$listid}]"] = 1;
		}
//dbg($subscriber);

		$subscriber_request = $this->api("subscriber/sync", $subscriber);
//dbg($subscriber_request);

		if ((int)$subscriber_request->success) {
			// successful request
			//$subscriber_id = (int)$subscriber_request->subscriber_id;
			$r = array(
				"success" => 1,
				"message" => $subscriber_request->result_message,
				//"subscriber_id" => $subscriber_id,
			);
		}
		else {
			// request failed
			$r = array(
				"success" => 0,
				"message" => $subscriber_request->error,
			);
		}

		return json_encode($r);
	}

}

?>