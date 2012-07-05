<?php

	require_once("ActiveCampaign.class.php");

	define("ACTIVECAMPAIGN_URL", "");
	define("ACTIVECAMPAIGN_API_KEY", "");

	$ac = new ActiveCampaign(ACTIVECAMPAIGN_URL, ACTIVECAMPAIGN_API_KEY);

	if (!(int)$ac->credentials_test()) {
		print_r("Invalid credentials (URL and API Key).");
		exit();
	}

	/*
	 *
	 * VIEW ACCOUNT DETAILS.
	 *
	 */

	$account = $ac->api("account/view");

	/*
	 *
	 * ADD NEW SUBSCRIBER.
	 *
	 */

	$subscriber = array(
		"email" => "me@test.com",
		"first_name" => "Matt",
		"last_name" => "Smith",
		"p[2]" => 2,
		"status[2]" => 2, // add as "Unsubscribed"
	);

	$subscriber_add = $ac->api("subscriber/add", $subscriber);

	if ((int)$subscriber_add->result_code) {
		// successful request
		$subscriber_id = (int)$subscriber_add->subscriber_id;
	}
	else {
		// request failed
		print_r($subscriber_add->result_message);
		exit();
	}

	/*
	 *
	 * EDIT SUBSCRIBER.
	 *
	 */

	$subscriber = array(
		"id" => $subscriber_id,
		"email" => "test@example.com",
		"first_name" => "Matt",
		"last_name" => "Test",
		"p[2]" => 2,
		"status[2]" => 1, // change the list status to "Subscribed"
	);

	$subscriber_edit = $ac->api("subscriber/edit?overwrite=0", $subscriber);

	/*
	 *
	 * ADD NEW EMAIL MESSAGE.
	 *
	 */

	$message = array(
		"format" => "mime",
		"subject" => "Check out our latest deals!",
		"fromemail" => "newsletter@test.com",
		"fromname" => "Test from API",
		"html" => "<p>My email newsletter body.</p>",
		"p[2]" => 2,
	);

	$message_add = $ac->api("message/add", $message);

	if ((int)$message_add->result_code) {
		// successful request
		$message_id = (int)$message_add->id;
	}
	else {
		// request failed
		print_r($message_add->result_message);
		exit();
	}

	/*
	 *
	 * CREATE NEW CAMPAIGN.
	 *
	 */

	$campaign = array(
		"type" => "single",
		"name" => "Campaign #44",
		"sdate" => "2012-01-01 00:00:00",
		"status" => 1,
		"public" => 1,
		"tracklinks" => "all",
		"trackreads" => 1,
		"htmlunsub" => 1,
		"p[2]" => 2,
		"m[{$message_id}]" => 100,
	);

	$campaign_create = $ac->api("campaign/create", $campaign);

	if ((int)$campaign_create->result_code) {
		// successful request
		$campaign_id = (int)$campaign_create->id;
		print_r("Campaign sent! (ID: {$campaign_id})");
	}
	else {
		// request failed
		print_r($campaign_create->result_message);
		exit();
	}

	/*
	 *
	 * VIEW CAMPAIGN REPORTS.
	 *
	 */

	$campaign_report_totals = $ac->api("campaign/report_totals?campaignid={$campaign_id}");

	echo "<p>Reports:</p>";
	echo "<pre>";
	print_r($campaign_report_totals);
	echo "</pre>";

?>