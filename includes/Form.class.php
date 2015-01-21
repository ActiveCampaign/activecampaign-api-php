<?php

class AC_Form extends ActiveCampaign {

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

				// replace the Submit button to be a button type (for ajax).
				// forms come out of AC now with a "submit" button (it used to be "button").
				$html = preg_replace("/input type='submit'/", "input type='button'", $html);

				$action_val = urldecode($action_val);

				// add jQuery stuff
				$extra = "<script type='text/javascript'>

var \$j = jQuery.noConflict();

\$j(document).ready(function() {

	\$j('#_form_{$id} input[type*=\"button\"]').click(function() {

		// rename the radio options for Subscribe/Unsubscribe, since they conflict with the hidden field.
		\$j('input[type=radio][name=act]').attr('name','act_radio');

		var form_data = {};
		\$j('#_form_{$id}').each(function() {
			form_data = \$j(this).serialize();
		});

		var geturl;
		geturl = \$j.ajax({
			url: '{$action_val}',
			type: 'POST',
			dataType: 'json',
			data: form_data,
			error: function(jqXHR, textStatus, errorThrown) {
				console.log(errorThrown);
			},
			success: function(data) {
				\$j('#form_result_message').html(data.message);
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

	function process($params) {

		$r = array();
		if ($_SERVER["REQUEST_METHOD"] != "POST") return $r;

		$sync = 0;
		if ($params) {
			$params_array = explode("&", $params);
			$params_ = array();
			foreach ($params_array as $expression) {
				// IE: css=1
				list($var, $val) = explode("=", $expression);
				$params_[$var] = $val;
			}

			$sync = (isset($params_["sync"])) ? (int)$params_["sync"] : 0;
		}

		$formid = $_POST["f"];
		// sub or unsub
		$act = isset($_POST["act"]) ? $_POST["act"] : "sub";
		if (isset($_POST["act_radio"])) {
			// the radio options for Subscribe/Unsubscribe.
			$act = $_POST["act_radio"];
		}
		$email = $_POST["email"];
		$phone = isset($_POST["phone"]) ? $_POST["phone"] : "";

		if (isset($_POST["fullname"])) {
			$fullname = explode(" ", $_POST["fullname"]);
			$firstname = array_shift($fullname);
			$lastname = implode(" ", $fullname);
		}
		else {
			$firstname = trim($_POST["firstname"]);
			$lastname = trim($_POST["lastname"]);
			if ($firstname == "" && isset($_POST["first_name"])) $firstname = trim($_POST["first_name"]);
			if ($lastname == "" && isset($_POST["last_name"])) $lastname = trim($_POST["last_name"]);
		}

		$fields = (isset($_POST["field"])) ? $_POST["field"] : array();

		$contact = array(
			"form" => $formid,
			"email" => $email,
			"first_name" => $firstname,
			"last_name" => $lastname,
			"phone" => $phone,
		);

		foreach ($fields as $ac_field_id => $field_value) {
			$contact["field"][$ac_field_id . ",0"] = $field_value;
		}

		// add lists
		$status = ($act == "unsub") ? 2 : 1;
		foreach ($_POST["nlbox"] as $listid) {
			$contact["p[{$listid}]"] = $listid;
			$contact["status[{$listid}]"] = $status;
		}

		if (!$sync) {

			// do add/edit

			$contact_exists = $this->api("contact/view?email={$email}", $contact);

			if ( !isset($contact_exists->id) ) {

				// contact does not exist - add them

				$contact_request = $this->api("contact/add", $contact);

				if ((int)$contact_request->success) {
					// successful request
					$contact_id = (int)$contact_request->subscriber_id;
					$r = array(
						"success" => 1,
						"message" => $contact_request->result_message,
						"contact_id" => $contact_id,
					);
				}
				else {
					// request failed
					$r = array(
						"success" => 0,
						"message" => $contact_request->error,
					);
				}

			}
			else {

				// contact already exists - edit them

				$contact_id = $contact_exists->id;

				$contact["id"] = $contact_id;

				$contact_request = $this->api("contact/edit?overwrite=0", $contact);

			}

		}
		else {

			// perform sync (add or edit)

			$contact_request = $this->api("contact/sync", $contact);

		}

		if ((int)$contact_request->success) {
			// successful request
			//$contact_id = (int)$contact_request->contact_id;
			$r = array(
				"success" => 1,
				"message" => $contact_request->result_message,
				//"contact_id" => $contact_id,
			);
		}
		else {
			// request failed
			$r = array(
				"success" => 0,
				"message" => $contact_request->error,
			);
		}

		return json_encode($r);
	}

}

?>