<?php

	require_once("includes/ActiveCampaign.class.php");

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
	 * ADD NEW LIST.
	 *
	 */

	$list = array(
		"name" => "List 3",
		"sender_name" => "My Company",
		"sender_addr1" => "123 S. Street",
		"sender_city" => "Chicago",
		"sender_zip" => "60601",
		"sender_country" => "USA",
	);

	$list_add = $ac->api("list/add", $list);

	if ((int)$list_add->success) {
		// successful request
		$list_id = (int)$list_add->id;
	}
	else {
		// request failed
		print_r($list_add->error);
		exit();
	}

	/*
	 *
	 * ADD NEW SUBSCRIBER.
	 *
	 */

	// CHECK IF THEY EXIST FIRST.
	$subscriber_exists = $ac->api("subscriber/view?email=test@example.com");

	if ( !isset($subscriber_exists->id) ) {

		// SUBSCRIBER DOES NOT EXIST - ADD THEM.

		$subscriber = array(
			"email" => "test@example.com",
			"first_name" => "Matt",
			"last_name" => "Test",
			"p[{$list_id}]" => $list_id,
			"status[{$list_id}]" => 2, // add as "Unsubscribed"
		);

		$subscriber_add = $ac->api("subscriber/add", $subscriber);

		if ((int)$subscriber_add->success) {
			// successful request
			$subscriber_id = (int)$subscriber_add->subscriber_id;
		}
		else {
			// request failed
			print_r($subscriber_add->error);
			exit();
		}
	}
	else {
		// SUBSCRIBER EXISTS - JUST GRAB THEIR ID.
		$subscriber_id = $subscriber_exists->id;
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
		"p[{$list_id}]" => $list_id,
		"status[{$list_id}]" => 1, // change to "Subscribed"
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
		"html" => "<p>My email newsletter.</p>",
		"p[{$list_id}]" => $list_id,
	);

	$message_add = $ac->api("message/add", $message);

	if ((int)$message_add->success) {
		// successful request
		$message_id = (int)$message_add->id;
	}
	else {
		// request failed
		print_r($message_add->error);
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
		"p[{$list_id}]" => $list_id,
		"m[{$message_id}]" => 100,
	);

	$campaign_create = $ac->api("campaign/create", $campaign);

	if ((int)$campaign_create->success) {
		// successful request
		$campaign_id = (int)$campaign_create->id;
		print_r("Campaign sent! (ID: {$campaign_id})");
	}
	else {
		// request failed
		print_r($campaign_create->error);
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