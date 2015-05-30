<?php

	require "vendor/autoload.php";

	$ac = new ActiveCampaign("API_URL", "API_KEY");

	if (!(int)$ac->credentials_test()) {
		echo "<p>Access denied: Invalid credentials (URL and/or API key).</p>";
		exit();
	}
    
    echo "<p>Credentials valid! Proceeding...</p>";

	/*
	 * VIEW ACCOUNT DETAILS.
	 */

	$account = $ac->api("account/view");

	echo "<pre>";
	print_r($account);
	echo "</pre>";

	/*
	 * ADD NEW LIST.
	 */

	$list = array(
		"name"           => "List 3",
		"sender_name"    => "My Company",
		"sender_addr1"   => "123 S. Street",
		"sender_city"    => "Chicago",
		"sender_zip"     => "60601",
		"sender_country" => "USA",
	);

	$list_add = $ac->api("list/add", $list);

	if (!(int)$list_add->success) {
		// request failed
		echo "<p>Adding list failed. Error returned: " . $list_add->error . "</p>";
		exit();
	}
    
    // successful request
	$list_id = (int)$list_add->id;
	echo "<p>List added successfully (ID {$list_id})!</p>";

	/*
	 * ADD OR EDIT CONTACT (TO THE NEW LIST CREATED ABOVE).
	 */

	$contact = array(
		"email"              => "test@example.com",
		"first_name"         => "Test",
		"last_name"          => "Test",
		"p[{$list_id}]"      => $list_id,
		"status[{$list_id}]" => 1, // "Active" status
	);

	$contact_sync = $ac->api("contact/sync", $contact);

	if (!(int)$contact_sync->success) {        
		// request failed
		echo "<p>Syncing contact failed. Error returned: " . $contact_sync->error . "</p>";
		exit();
	}

	// successful request
	$contact_id = (int)$contact_sync->subscriber_id;
	echo "<p>Contact synced successfully (ID {$contact_id})!</p>";

	/*
	 * VIEW ALL CONTACTS IN A LIST (RETURNS ID AND EMAIL).
	 */

	$ac->version(2);
	$contacts_view = $ac->api("contact/list?listid=14&limit=500");

	$ac->version(1);

	/*
	 * ADD NEW EMAIL MESSAGE (FOR A CAMPAIGN).
	 */

	$message = array(
		"format"        => "mime",
		"subject"       => "Check out our latest deals!",
		"fromemail"     => "newsletter@test.com",
		"fromname"      => "Test from API",
		"html"          => "<p>My email newsletter.</p>",
		"p[{$list_id}]" => $list_id,
	);

	$message_add = $ac->api("message/add", $message);

	if (!(int)$message_add->success) {
		// request failed
		echo "<p>Adding email message failed. Error returned: " . $message_add->error . "</p>";
		exit();
	}
		
    // successful request
	$message_id = (int)$message_add->id;
	echo "<p>Message added successfully (ID {$message_id})!</p>";

	/*
	 * CREATE NEW CAMPAIGN (USING THE EMAIL MESSAGE CREATED ABOVE).
	 */

	$campaign = array(
		"type"             => "single",
		"name"             => "July Campaign", // internal name (message subject above is what contacts see)
		"sdate"            => "2013-07-01 00:00:00",
		"status"           => 1,
		"public"           => 1,
		"tracklinks"       => "all",
		"trackreads"       => 1,
		"htmlunsub"        => 1,
		"p[{$list_id}]"    => $list_id,
		"m[{$message_id}]" => 100, // 100 percent of subscribers
	);

	$campaign_create = $ac->api("campaign/create", $campaign);

	if (!(int)$campaign_create->success) {
		// request failed
		echo "<p>Creating campaign failed. Error returned: " . $campaign_create->error . "</p>";
		exit();
	}
		
    // successful request
	$campaign_id = (int)$campaign_create->id;
	echo "<p>Campaign created and sent! (ID {$campaign_id})!</p>";

	/*
	 * VIEW CAMPAIGN REPORTS (FOR THE CAMPAIGN CREATED ABOVE).
	 */

	$campaign_report_totals = $ac->api("campaign/report/totals?campaignid={$campaign_id}");

	echo "<p>Reports:</p>";
	echo "<pre>";
	print_r($campaign_report_totals);
	echo "</pre>";

?>

<a href="http://www.activecampaign.com/api">View more API examples!</a>
